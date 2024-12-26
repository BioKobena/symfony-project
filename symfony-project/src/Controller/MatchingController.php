<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MatchingController extends AbstractController
{
    #[Route('/matching', name: 'app_matching')]
    public function index(): Response
    {
        return $this->render('matching/index.html.twig', [
            'controller_name' => 'MatchingController',
        ]);
    }

    #[Route('/matching/match', name: 'match')]
    public function matching(): Response
    {
        return $this->render('matching/match.html.twig', [
            'controller_name' => 'MatchingController',
        ]);
    }
}
