<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Developer;
class MatchingController extends AbstractController
{
    // Matching Dev --> Entreprise 
    #[Route('/matching', name: 'app_matching')]
    public function index(): Response
    {
        return $this->render('matching/index.html.twig', [
            'controller_name' => 'MatchingController',
        ]);
    }

    // Matching Entreprise --> Dev
    #[Route('/matching-dev', name: 'match-dev')]
    public function matching_dev(EntityManagerInterface $entityManager): Response
    {
        $developers = $entityManager->getRepository(Developer::class)->findAll();

        // Récupérer les 3 derniers développeurs créés
        return $this->render('matching/matching_entreprise.html.twig', [
            'developers' => $developers,
        ]);
    }
}
