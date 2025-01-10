<?php

namespace App\Form;

use App\Entity\Developer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class DeveloperType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
            ])
            ->add('localisation', TextType::class, [
                'label' => 'Localisation',
            ])
            ->add('languages', ChoiceType::class, [
                'label' => 'Langages de programmation',
                'choices' => [
                    'Python' => 'python',
                    'JavaScript' => 'javascript',
                    'Java' => 'java',
                    'C++' => 'cpp',
                    'PHP' => 'php',
                ],
                'placeholder' => 'Sélectionnez un langage',
                'attr' => [
                    'class' => 'form-control',
                ],
                'multiple' => true, // Permet la sélection multiple si nécessaire
                'expanded' => false, // Choix sous forme de liste déroulante (false) ou boutons radio (true)
            ])
            ->add('experience', TextType::class, [
                'label' => 'Années d’expérience',
            ])
            ->add('salaire_min', TextType::class, [
                'label' => 'Salaire minimum',
            ])
            ->add('bio', TextType::class, [
                'label' => 'Biographie',
            ])
            ->add('avatar', FileType::class, [
                'label' => 'Avatar',
                'mapped' => true,
                'required' => false,
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse e-mail',
                'mapped' => false, // Non lié à Developer
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'mapped' => false, // Non lié à Developer
            ])
            ->add('confirmPassword', PasswordType::class, [
                'label' => 'Confirmez le mot de passe',
                'mapped' => false, // Non lié à Developer
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Developer::class,
        ]);
    }
}
