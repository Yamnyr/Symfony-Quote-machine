<?php
/*
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }
}*/


namespace App\Controller;

use App\Entity\User;
use App\Repository\QuoteRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route(path: '/profile/{id}', name: 'app_profile')]
    #[IsGranted('ROLE_USER')]
    public function profile(User $user, QuoteRepository $quoteRepository): Response
    {
        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'last_created_quotes' => $quoteRepository->findLastCreatedByUser($user),
        ]);
    }
}

