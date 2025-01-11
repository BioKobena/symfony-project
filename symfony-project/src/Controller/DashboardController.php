<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\DeveloperRepository;
use App\Entity\Company;
use App\Entity\Developer;
use App\Repository\CompanyRepository;
use App\Entity\FicheDePoste;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Favoris;


class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(DeveloperRepository $developerRepository, EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Vérifier que l'utilisateur est bien connecté
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        // Récupérer le développeur correspondant à l'utilisateur connecté
        $developer = $developerRepository->findOneBy(['user' => $user]);

        // Exemple de données pour les statistiques
        $favorites_count = 20; // Nombre de fois ajouté en favoris
        $recruiters_response_rate = 75; // Taux de réponse des recruteurs

        // Calcul des pourcentages (vous pouvez ajuster cette logique en fonction des données réelles)
        $favorites_count_percentage = ($favorites_count / 20) * 100; // 30 est un maximum arbitraire

        $favoris = $entityManager->getRepository(Favoris::class)
            ->findBy(['developer' => $developer]);


        // Les postes récents
        $fichesDePoste = $entityManager->getRepository(FicheDePoste::class)
            ->findBy([], ['createdAt' => 'DESC'], 3);

        // Les postes populaires
        $offres = $entityManager->getRepository(FicheDePoste::class)
            ->findBy([], ['views' => 'DESC'], 3);
        $nombreFavoris = count($favoris);
        // Passer toutes les données nécessaires au template Twig
        return $this->render('dashboard/index.html.twig', [
            'developer' => $developer,
            'jobs' => $fichesDePoste,
            'favorites_count' => $nombreFavoris,
            'favorites_count_percentage' => $favorites_count_percentage,
            'recruiters_response_rate' => $recruiters_response_rate,
            'suggested_jobs' => $offres,
            'latest_jobs' => $offres,
        ]);
    }



    #[Route('/dashboard-entreprise', name: 'app_dashboard_entreprise')]
    public function index_entreprise(CompanyRepository $companyRepository, EntityManagerInterface $entityManager): Response
    {

        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Vérifier que l'utilisateur est bien connecté
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        // Récupérer le développeur correspondant à l'utilisateur connecté
        $company = $companyRepository->findOneBy(['user' => $user]);

        $fichesDePoste = $entityManager->getRepository(FicheDePoste::class)
            ->findBy(['company' => $company]);

        // Calculer la somme des vues
        $totalVues = array_reduce($fichesDePoste, function ($sum, $fiche) {
            return $sum + $fiche->getViews(); // Assurez-vous que la méthode getVues() retourne le nombre de vues
        }, 0);
        // Définir des données statiques
        $recruiters_response_rate = 78;
        $favorites_count_percentage = 50;

        $nombreFiche = count($fichesDePoste);

        $developers = $entityManager->getRepository(Developer::class)
            ->findBy([], ['id' => 'DESC'], 3); // Les 3 derniers développeurs

        $favoris = $entityManager->getRepository(Favoris::class)
            ->findBy(['company' => $company]);


        $nombreFavoris = count($favoris);

        // Passer les données à la vue
        return $this->render('dashboard/dashboardEnterprise.html.twig', [
            'profile_views' => $nombreFiche,
            'applications_sent' => $totalVues,
            'recruiters_response_rate' => $recruiters_response_rate,
            'favorites_count' => $nombreFavoris,
            'favorites_count_percentage' => $favorites_count_percentage,
            'jobs' => $developers,
            'suggested_jobs' => $developers,
            'company' => $company
        ]);
    }

}

