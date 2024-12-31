<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\FicheDePoste;
use Doctrine\ORM\EntityManagerInterface;


class OffreController extends AbstractController
{
    #[Route('/offre', name: 'app_offre')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $fichesDePoste = $entityManager->getRepository(FicheDePoste::class)
        ->findAll();

        $offres = $entityManager->getRepository(FicheDePoste::class)->findAll();

        // Récupérer les 3 derniers développeurs créés
        return $this->render('offre/index.html.twig', [
            'fiches_de_poste' => $fichesDePoste,
        ]);
        // return $this->render('offre/index.html.twig', [
        //     'controller_name' => 'OffreController',
        // ]);
    }


    #[Route('/{id}/offres', name: 'app_detail')]
    public function detail($id, EntityManagerInterface $entityManager): Response
    {
        $fiche = $entityManager->getRepository(FicheDePoste::class)->find($id);

        if (!$fiche) {
            throw $this->createNotFoundException('Offre introuvable');
        }

        $fiche->incrementViews();

        $entityManager->flush();

        return $this->render('offre/detail.html.twig', [
            'fiche' => $fiche,
        ]);
    }

    #[Route('/success', name: 'app_success')]
    public function success(): Response
    {
        return $this->render('offre/success.html.twig', [
            'controller_name' => 'OffreController',
        ]);
    }

}
