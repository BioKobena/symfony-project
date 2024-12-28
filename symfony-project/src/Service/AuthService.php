<?php

namespace App\Service;

use App\Entity\Developer;
use Doctrine\ORM\EntityManagerInterface;

class AuthService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function authenticateDeveloper(string $email, string $password): ?Developer
    {
        $developer = $this->entityManager->getRepository(Developer::class)->findOneBy(['email' => $email]);

        if ($developer && password_verify($password, $developer->getPassword())) {
            return $developer;
        }

        return null;
    }
}
