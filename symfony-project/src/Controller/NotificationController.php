<?php

// src/Controller/NotificationController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\DeveloperRepository;
use App\Repository\CompanyRepository;
use App\Service\NotificationService;
use App\Service\MatchingService;

class NotificationController extends AbstractController
{
    #[Route('/notifications-dev', name: 'app_notifications')]
    public function index(DeveloperRepository $developerRepository, ): Response
    {
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

        // $developer = $this->getUser(); // Supposons que le développeur est connecté
        $notifications = $developer->getNotifications();

        return $this->render('notification/index.html.twig', [
            'notifications' => $notifications,
        ]);
    }

    #[Route('/company-notifications', name: 'app_notifications_company')]
    public function companyNotifications(CompanyRepository $companyRepository): Response
    {
        // Récupérer l'entreprise par ID
        $company = $this->getUser()->getCompany();

        if (!$company) {
            throw $this->createNotFoundException('Entreprise non trouvée.');
        }

        // Récupérer les notifications associées à cette entreprise
        $notifications = $company->getNotifications();

        return $this->render('notification/notification_company.html.twig', [
            'company' => $company,
            'notifications' => $notifications,
        ]);
    }


}
