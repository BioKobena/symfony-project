<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_register', methods: ['GET', 'POST'])]
    public function register(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        if ($request->isMethod('POST')) {
            // Récupération des données POST
            $firstName = $request->request->get('firstName');
            $lastName = $request->request->get('lastName');
            $email = $request->request->get('email');
            $password = $request->request->get('password');
            $location = $request->request->get('location');
            $bio = $request->request->get('bio');
            $profilePicture = $request->files->get('profilePicture'); // Récupérer l'image uploadée

            // Validation basique
            if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
                $this->addFlash('error', 'Veuillez remplir tous les champs obligatoires.');
                return $this->redirectToRoute('app_register');
            }

            // Création d'un nouvel utilisateur
            $user = new User();
            $user->setFirstName($firstName);
            $user->setLastName($lastName);
            $user->setEmail($email);
            $user->setLocation($location);
            $user->setBio($bio);

            // Sauvegarde du mot de passe
            if (!empty($password)) {
                $user->setPassword($password);
            } else {
                $this->addFlash('error', 'Le mot de passe est requis.');
                return $this->redirectToRoute('app_register');
            }

            // Gestion de l'image
            if ($profilePicture instanceof UploadedFile) {
                $uploadsDirectory = $this->getParameter('uploads_directory');

                $newFilename = uniqid() . '.' . $profilePicture->guessExtension();

                try {
                    $profilePicture->move($uploadsDirectory, $newFilename);
                    $user->setProfilePicture($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors du téléchargement de l\'image.');
                    return $this->redirectToRoute('app_register');
                }
            }

            // Sauvegarde en base de données
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Votre compte a été créé avec succès.');
        }

        return $this->render('user/index.html.twig');
    }

    #[Route('/users', name: 'app_user_list', methods: ['GET'])]
    public function list(EntityManagerInterface $entityManager): Response
    {
        // Récupérer tous les utilisateurs
        $users = $entityManager->getRepository(User::class)->findAll();

        // Retourner les utilisateurs à la vue
        return $this->render('user/list.html.twig', [
            'users' => $users,
        ]);
    }
}
