<?php

namespace App\Tests\Command;

use App\Factory\CategoryFactory;
use App\Factory\QuoteFactory;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class RandomQuoteCommandTest extends KernelTestCase
{
    private Application $application;

    public function setUp(): void
    {
        $kernel = static::bootKernel();
        $this->application = new Application($kernel);
    }

    public function testItDisplayWarningWhenNoQuoteInDatabase(): void
    {
        $command = $this->application->find('app:random-quote');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
        ]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Aucune citation trouvée', $output);
    }

    public function testItDisplayRandomQuote(): void
    {
        QuoteFactory::createOne([
            'content' => 'C’est pas faux.',
            'meta' => 'Perceval',
        ]);

        $command = $this->application->find('app:random-quote');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
        ]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('C’est pas faux.', $output);
    }

    public function testItDisplayRandomQuoteFilteredByCategory(): void
    {
        QuoteFactory::createOne([
            'content' => 'C’est pas faux.',
            'meta' => 'Perceval',
            'category' => CategoryFactory::new([
                'name' => 'Kaamelott',
            ]),
        ]);
        QuoteFactory::createOne([
            'content' => 'Comment est votre blanquette ?',
            'meta' => 'OSS 117',
        ]);

        $command = $this->application->find('app:random-quote');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            '--category' => 'Kaamelott',
        ]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('C’est pas faux.', $output);
    }
}
