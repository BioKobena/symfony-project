<?php

namespace App\Controller;

use App\Entity\Developer;
use App\Repository\CompanyRepository;
use App\Repository\DeveloperRepository;
use App\Repository\FicheDePosteRepository;
use App\Service\MatchingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
class MatchingController extends AbstractController
{
    // Matching Dev --> Entreprise 
    #[Route('/matching', name: 'app_matching')]
    public function index(
        DeveloperRepository $developerRepository,
        FicheDePosteRepository $jobRepository,
        MatchingService $matchingService
    ): Response {

        $developer = $developerRepository->find(17); // Exemple d'ID

        // Récupérer tous les postes
        $jobs = $jobRepository->findAll();

        // Calculer les correspondances
        $matches = [];
        foreach ($jobs as $job) {
            $score = $matchingService->calculateMatchScore($developer, $job);
            $matches[] = [
                'job' => $job,
                'score' => $score,
                'entreprise' => $job->getEntreprise(),
            ];
        }

        // Trier les résultats par score décroissant
        usort($matches, fn($a, $b) => $b['score'] <=> $a['score']);

        // Rendre la vue avec les correspondances
        return $this->render('matching/index.html.twig', [
            'developer' => $developer,
            'matches' => $matches,
        ]);

        // return $this->render('matching/index.html.twig', [
        //     'controller_name' => 'MatchingController',
        // ]);
    }

    // Matching Entreprise --> Dev
    // #[Route('/matching-dev', name: 'match-dev')]
    // public function matching_dev(EntityManagerInterface $entityManager): Response
    // {
    //     $developers = $entityManager->getRepository(Developer::class)->findAll();

    //     // Récupérer les 3 derniers développeurs créés
    //     return $this->render('matching/matching_entreprise.html.twig', [
    //         'developers' => $developers,
    //     ]);
    // }

    #[Route('/matching-companies', name: 'match-dev')]
    public function matchDevelopersToCompany(
        CompanyRepository $companyRepository,
        DeveloperRepository $developerRepository,
        MatchingService $matchingService
    ): Response {
        // Récupérer l'entreprise par ID
        $company = $companyRepository->find(3);

        if (!$company) {
            throw $this->createNotFoundException('Entreprise non trouvée.');
        }

        // Récupérer tous les développeurs
        $developers = $developerRepository->findAll();

        // Calculer les correspondances
        $matches = [];
        foreach ($developers as $developer) {
            // Liste des postes correspondant au développeur
            $matchingJobs = [];
            foreach ($company->getFichesDePostes() as $job) {
                $jobScore = $matchingService->calculateMatchScore($developer, $job);
                if ($jobScore > 49) {
                    $matchingJobs[] = [
                        'job' => $job,
                        'score' => $jobScore,
                    ];
                }
            }

            // Calculer le score global pour le développeur et l'entreprise
            $developerScore = $matchingService->calculateDeveloperMatchScore($company, $developer);

            if ($developerScore > 49) {
                $matches[] = [
                    'developer' => $developer,
                    'score' => $developerScore,
                    'matchingJobs' => $matchingJobs, // Ajouter les postes correspondants
                ];
            }
        }

        // Trier les résultats par score décroissant
        usort($matches, fn($a, $b) => $b['score'] <=> $a['score']);

        // Rendre la vue avec les correspondances
        return $this->render('matching/matching_entreprise.html.twig', [
            'company' => $company,
            'matches' => $matches,
        ]);
    }
}
