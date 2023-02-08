<?php

namespace App\Command;

use App\Entity\Quote;
use App\Repository\CategoryRepository;
use App\Repository\QuoteRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:random-quote',
    description: 'Display a random quote',
)]
class RandomQuoteCommand extends Command
{
    private QuoteRepository $quoteRepository;

    private CategoryRepository $categoryRepository;

    public function __construct(QuoteRepository $quoteRepository, CategoryRepository $categoryRepository)
    {
        $this->quoteRepository = $quoteRepository;
        $this->categoryRepository = $categoryRepository;

        parent::__construct();
    }

    protected function configure()
    {
        $this->addOption('category', null, InputOption::VALUE_REQUIRED, 'Filter by the given category');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $categoryName = $input->getOption('category');

        if (null === $categoryName) {
            $quote = $this->quoteRepository->random();
            $output->writeln('Voici un citation aléatoire :');
            $output->write('================================================================');
            $this->displayQuote($io, $quote);
            $output->writeln('================================================================');

            return self::SUCCESS;
        }

        $category = $this->categoryRepository->findOneByName($categoryName);

        if (null === $category) {
            $io->error(sprintf('Catégorie "%s" inconnue.', $categoryName));

            return self::FAILURE;
        }

        $output->writeln("Voici un citation aléatoire provenant de $categoryName :");
        $output->write('================================================================');
        $quote = $this->quoteRepository->random($category);
        $this->displayQuote($io, $quote);
        $output->writeln('================================================================');

        return self::SUCCESS;
    }

    private function displayQuote(SymfonyStyle $io, ?Quote $quote): void
    {
        if (null === $quote) {
            $io->warning('Aucune citation trouvée');

            return;
        }

        $io->block($quote->getContent());
        $io->text($quote->getMeta());
    }
}
