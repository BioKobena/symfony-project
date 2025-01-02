<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Developer;

class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $developers = $entityManager->getRepository(Developer::class)->findAll();

        // Récupérer les 3 derniers développeurs créés
        return $this->render('profil/index.html.twig', [
            'developers' => $developers,
        ]);
    }

    // #[Route('/profile', name: 'app_profil')]
    // public function accueil(): Response
    // {
    //     return $this->render('profil/profile.html.twig', [
    //         'controller_name' => 'ProfilController',
    //     ]);
    // }
}