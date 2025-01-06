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
use App\Service\NotificationService;
class MatchingController extends AbstractController
{
    // Matching Dev --> Entreprise 
    #[Route('/matching', name: 'app_matching')]
    public function index(
        DeveloperRepository $developerRepository,
        FicheDePosteRepository $jobRepository,
        MatchingService $matchingService
    ): Response {

        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Vérifier que l'utilisateur est bien connecté
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        // Récupérer le développeur correspondant à l'utilisateur connecté
        $developer = $developerRepository->findOneBy(['user' => $user]);

        // Vérifier que le développeur existe
        if (!$developer) {
            throw $this->createNotFoundException('Développeur non trouvé pour cet utilisateur.');
        }

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
        MatchingService $matchingService,
        NotificationService $notificationService
    ): Response {
        // Récupérer l'entreprise associée à l'utilisateur connecté
        $company = $this->getUser()->getCompany();

        if (!$company) {
            // throw $this->createNotFoundException('Entreprise non trouvée.');
            return $this->redirectToRoute('app_company_login');
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
                if ($jobScore >= 50) {
                    $matchingJobs[] = [
                        'job' => $job,
                        'score' => $jobScore,
                    ];
                    $notificationService->notifyCompanyAboutMatchingDeveloper($company, $developer, $job);
                }
            }

            // Calculer le score global pour le développeur et l'entreprise
            $developerScore = $matchingService->calculateDeveloperMatchScore($company, $developer);

            if ($developerScore >= 49) {
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
