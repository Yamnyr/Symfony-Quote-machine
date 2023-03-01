<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewQuoteIndicatorComponentController extends AbstractController
{
    #[Route('/new/quote/indicator/component', name: 'app_new_quote_indicator_component')]
    public function index(): Response
    {
        return $this->render('new_quote_indicator_component/index.html.twig', [
            'controller_name' => 'NewQuoteIndicatorComponentController',
        ]);
    }
}
