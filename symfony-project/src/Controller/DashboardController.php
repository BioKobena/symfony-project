<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {
        // Exemple de données pour un développeur
        $developer = [
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'localisation' => 'Paris, France',
        ];

        // Données fictives pour les postes suggérés
        $suggestedJobs = [
            ['titre' => 'Développeur PHP', 'localisation' => 'Paris', 'salaire' => '40k', 'id' => 1, 'image' => '/images/tiny_tots.jpg',],
            ['titre' => 'Développeur Frontend', 'localisation' => 'Lyon', 'salaire' => '45k', 'id' => 2, 'image' => '/images/little_explorers.jpg',],
            ['titre' => 'Développeur PHP', 'localisation' => 'Paris', 'salaire' => '40k', 'id' => 1, 'image' => '/images/tiny_tots.jpg',],
            ];

        // Données fictives pour les postes populaires
        $jobs = [
            [
                'logo' => 'https://via.placeholder.com/50', // Remplacez par l'URL de votre logo
                'company' => 'KPMG France',
                'title' => 'Stagiaire Forensic F/H',
                'type' => 'Stage 4 à 6 mois',
                'location' => 'Courbevoie, France',
                'tag' => 'Offre partenaire',
                'is_offer_of_week' => true,
            ],
            // Ajoutez d'autres jobs ici...
        ];

        // Données fictives pour les dernières offres publiées
        $latestJobs = [
            ['titre' => 'Développeur Symfony', 'localisation' => 'Toulouse', 'salaire' => '50k', 'id' => 4],
        ];

        // Exemple de données pour les statistiques
        $profile_views = 150; // Nombre de vues du profil
        $applications_sent = 40; // Nombre de candidatures envoyées
        $favorites_count = 20; // Nombre de fois ajouté en favoris
        $recruiters_response_rate = 75; // Taux de réponse des recruteurs

        // Calcul des pourcentages (vous pouvez ajuster cette logique en fonction des données réelles)
        $profile_views_percentage = ($profile_views / 200) * 100; // Par exemple, 200 est un maximum arbitraire
        $applications_sent_percentage = ($applications_sent / 50) * 100; // 50 est un maximum arbitraire
        $favorites_count_percentage = ($favorites_count / 20) * 100; // 30 est un maximum arbitraire
        $new_clients_count = 236;
        $new_clients_percentage = 18.33;
        $earnings_of_month = 18306;
        $new_projects_count = 1538;
        $new_projects_percentage = 18.33;
        $projects_count = 864;

        // Passer toutes les données nécessaires au template Twig
        return $this->render('dashboard/index.html.twig', [
            'developer' => $developer,
            'jobs' => $jobs,
            'profile_views' => $profile_views,
            'profile_views_percentage' => $profile_views_percentage,
            'applications_sent' => $applications_sent,
            'applications_sent_percentage' => $applications_sent_percentage,
            'favorites_count' => $favorites_count,
            'favorites_count_percentage' => $favorites_count_percentage,
            'recruiters_response_rate' => $recruiters_response_rate,
            'suggested_jobs' => $suggestedJobs,
            'latest_jobs' => $latestJobs,
            'new_clients_count' => $new_clients_count,
            'new_clients_percentage' => $new_clients_percentage,
            'earnings_of_month' => $earnings_of_month,
            'new_projects_count' => $new_projects_count,
            'new_projects_percentage' => $new_projects_percentage,
            'projects_count' => $projects_count,
        ]);
    }
}
