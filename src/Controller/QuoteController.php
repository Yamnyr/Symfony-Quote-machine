<?php

namespace App\Controller;

use App\Entity\Quote;
use App\Form\QuoteType;
use App\Repository\QuoteRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuoteController extends AbstractController
{
    #[Route('/quote', name: 'quote_index')]
    public function index(Request $request, QuoteRepository $quoteRepository): Response
    {
        $queryBuilder = $quoteRepository->createQueryBuilder('q');

        $search = $request->query->get('search');
        if (!empty($search)) {
            $queryBuilder->where('q.content LIKE :search')->setParameter('search', '%' . $search . '%');
        }

        return $this->render('quote/index.html.twig', [
            'quotes' => $queryBuilder->getQuery()->getResult(),
        ]);
    }

    #[Route('/quote/{id}/delete', name: 'quote_delete')]
    #[IsGranted('ROLE_USER')]
    public function delete(ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $quote = $entityManager->getRepository(Quote::class)->find($id);

        $entityManager->remove($quote);
        $entityManager->flush();

        return $this->redirectToRoute('quote_index');
    }

    #[Route('/quote/{id}/edit', name: 'quote_edit')]
    #[IsGranted('ROLE_USER')]
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
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        $quote = new Quote();

        $form = $this->createForm(QuoteType::class, $quote);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($quote);
            $entityManager->flush();

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
}
