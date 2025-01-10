<?php

namespace App\Form;

use App\Entity\Company; // Remplace par ton namespace réel
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de l\'entreprise',
            ])
            ->add('localisation', TextType::class, [
                'label' => 'Localisation',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
            ])
            ->add('taille_entreprise', ChoiceType::class, [
                'label' => 'Taille de l\'entreprise',
                'multiple' => true,
                'expanded' => true,
                'choices' => [
                    '1 à 10 employés' => '1-10',
                    '11 à 50 employés' => '11-50',
                    '51 à 200 employés' => '51-200',
                    '200+ employés' => '200+',
                ],
            ])
            ->add('secteur', ChoiceType::class, [
                'label' => 'Secteur d\'activité',
                'multiple' => true,
                'expanded' => true,
                'choices' => [
                    'Technologie' => 'technology',
                    'Finance' => 'finance',
                    'Éducation' => 'education',
                    'Santé' => 'health',
                    'Autres' => 'others',
                ],
            ])
            ->add('type_entreprise', ChoiceType::class, [
                'label' => 'Type d\'entreprise',
                'multiple' => true,
                'expanded' => true,
                'choices' => [
                    'Société par actions' => 'public',
                    'Entreprise privée' => 'private',
                    'Startup' => 'startup',
                    'Entreprise individuelle' => 'sole proprietorship',
                ],
            ])
            ->add('avatar', FileType::class, [
                'label' => 'Logo de l\'entreprise',
                'required' => false,
                'mapped' => true,
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
            'data_class' => Company::class, // Remplace par ton entité réelle
        ]);
    }
}
