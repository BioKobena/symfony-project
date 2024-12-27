<?php

namespace App\Controller;

use App\Entity\DeveloperProfile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{

    // Développeurs
    #[Route('/auth', name: 'app_auth')]
    public function index(): Response
    {
        return $this->render('auth/index.html.twig', [
            'controller_name' => 'AuthController',
        ]);
    }

    //Inscription developper
    // #[Route('/auth/signin', name: 'app_signin')]
    // public function signin(): Response
    // {
    //     return $this->render('user/index.html.twig', [
    //         'controller_name' => 'AuthController',
    //     ]);
    // }
    #[Route('/auth/signin', name: 'app_signin', methods: ['GET', 'POST'])]
    public function register(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $formData = $request->request->all();
            $avatar = $request->files->get('avatar');

            // Validation des mots de passe
            if ($formData['password'] !== $formData['confirmPassword']) {
                $this->addFlash('error', 'Les mots de passe ne correspondent pas.');
                return $this->redirectToRoute('app_signin');
            }

            // Création de l'entité
            $developerProfile = new DeveloperProfile();
            $developerProfile->setFirstName($formData['firstName']);
            $developerProfile->setLastName($formData['lastName']);
            $developerProfile->setEmail($formData['email']);
            $developerProfile->setLocation($formData['location']);
            $developerProfile->setProgrammingLanguages(implode(',', $formData['languages'] ?? []));
            $developerProfile->setExperienceLevel((int) $formData['experience']);
            $developerProfile->setMinSalary((int) $formData['salary']);
            $developerProfile->setBiography($formData['bio']);

            // Gestion de l'avatar
            if ($avatar) {
                $avatarFilename = uniqid() . '.' . $avatar->guessExtension();
                $avatar->move($this->getParameter('avatars_directory'), $avatarFilename);
                $developerProfile->setAvatar($avatarFilename);
            }

            // Sauvegarde dans la base de données
            $entityManager->persist($developerProfile);
            $entityManager->flush();

            $this->addFlash('success', 'Inscription réussie.');
            return $this->redirectToRoute('app_signin');
        }

        return $this->render('auth/signin.html.twig');
    }


    // Connexion entreprise
    #[Route('/auth-company', name: 'auth-company')]
    public function login_company(): Response
    {
        return $this->render('auth/auth-company.html.twig', [
            'controller_name' => 'AuthController',
        ]);
    }

    #[Route('/signin-company', name: 'signin-company')]
    public function signin_company(): Response
    {
        return $this->render('auth/signin-company.html.twig', [
            'controller_name' => 'AuthController',
        ]);
    }
}
