<?php

namespace App\Controller;

use App\Entity\Quote;
use App\Event\QuoteCreated;
use App\Form\QuoteType;
use App\Repository\QuoteRepository;
use App\Security\Voter\QuoteVoter;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;


class QuoteController extends AbstractController
{
    #[Route('/quote', name: 'quote_index')]
    public function index(Request $request, QuoteRepository $quoteRepository): Response
    {
        $queryBuilder = $quoteRepository->createQueryBuilder('q');

        $search = $request->query->get('search');
        if (!empty($search)) {
            $queryBuilder->where('q.content LIKE :search')->setParameter('search', '%'.$search.'%');
        }
        $queryBuilder->orderBy('q.date_creation', 'DESC');

        return $this->render('quote/index.html.twig', [
            'quotes' => $queryBuilder->getQuery()->getResult(),
        ]);
    }

    #[Route('/quote/{id}/delete', name: 'quote_delete')]
    #[IsGranted(QuoteVoter::DELETE, subject: 'quote')]
    public function delete(ManagerRegistry $doctrine, Quote $quote, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $quote = $entityManager->getRepository(Quote::class)->find($id);

        $entityManager->remove($quote);
        $entityManager->flush();

        return $this->redirectToRoute('quote_index');
    }

    #[Route('/quote/{id}/edit', name: 'quote_edit')]
    #[IsGranted(QuoteVoter::EDIT, subject: 'quote')]
    public function update(ManagerRegistry $doctrine, Quote $quote, Request $request): Response
    {
        $form = $this->createForm(QuoteType::class, $quote);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('quote_index');
        }

        return $this->renderForm('quote/edit.html.twig', [
            'quote' => $quote,
            'form' => $form,
        ]);

        /*$entityManager = $doctrine->getManager();
        $quote = $entityManager->getRepository(Quote::class)->find($id);

        if (!$quote) {
            throw $this->createNotFoundException(
                'Aucunne citation trouvée pour l\'identifiant '.$id
            );
        }
        if ($request->isMethod('POST')){
            $content = $request->request->get('content');
            $meta = $request->request->get('meta');
            $quote->setContent($content);
            $quote->setMeta($meta);
            $entityManager->persist($quote);
            $entityManager->flush();
            return $this->redirectToRoute('quote_index');
        }
        return $this->render('quote/edit.html.twig',[
            'quote' => $quote
        ]);*/
    }

    #[Route('/quote/new', name: 'quote_new')]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, ManagerRegistry $doctrine, EventDispatcherInterface $eventDispatcher): Response
    {
        $quote = new Quote();

        $form = $this->createForm(QuoteType::class, $quote);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($quote);
            $entityManager->flush();

            $eventDispatcher->dispatch(new QuoteCreated($quote));

            return $this->redirectToRoute('quote_index');
        }

        return $this->renderForm('quote/new.html.twig', [
            'quote' => $quote,
            'form' => $form,
        ]);

        /*$entityManager = $doctrine->getManager();
        if ($request->isMethod('POST')){
            $quote=new Quote();
            $content = $request->request->get('content');
            $meta = $request->request->get('meta');
            $quote->setContent($content);
            $quote->setMeta($meta);
            $entityManager->persist($quote);
            $entityManager->flush();
            return $this->redirectToRoute('quote_index');
        }*/
    }

    #[Route('/quote.csv', name: 'quote_csv')]
    public function exportCsv(SerializerInterface $serializer, QuoteRepository $quoteRepository): Response
    {
        $quotes = $quoteRepository->findAll();

        $csv = $serializer->serialize($quotes, 'csv', [
            'groups' => 'csv',
            AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
        ]);

        $response = new Response($csv);

        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            'quote.csv'
        );

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', $disposition);

        return $response;
    }

}
