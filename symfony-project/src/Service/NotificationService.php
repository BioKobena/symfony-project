<?php

namespace App\Service;

use App\Entity\Notification;
use App\Entity\Developer;
use App\Entity\FicheDePoste;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Company;

class NotificationService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function notifyCompanyAboutMatchingDeveloper(Company $company, Developer $developer, FicheDePoste $job): void
    {
        // Vérifier si une notification similaire existe déjà
        $existingNotification = $this->entityManager->getRepository(Notification::class)->findOneBy([
            'company' => $company,
            'developer' => $developer,
            'job' => $job,
        ]);

        // Si une notification existe déjà, ne rien faire
        if ($existingNotification) {
            return;
        }

        // Sinon, créer une nouvelle notification
        $notification = new Notification();
        $notification->setMessage(sprintf(
            'Un nouveau développeur (%s) correspond à la fiche de poste "%s".',
            $developer->getNom(),
            $job->getTitre()
        ));
        $notification->setCreatedAt(new \DateTimeImmutable());
        $notification->setCompany($company);
        $notification->setDeveloper($developer);
        $notification->setJob($job);

        $this->entityManager->persist($notification);
        $this->entityManager->flush();
    }

    public function notifyDeveloper(Developer $developer, FicheDePoste $job): void
    {
        $notification = new Notification();
        $notification->setMessage(sprintf("Une nouvelle offre correspond à votre profil : %s", $job->getTitre()));
        $notification->setDeveloper($developer);
        $notification->setJob($job);

        $this->entityManager->persist($notification);
        $this->entityManager->flush();
    }
}
