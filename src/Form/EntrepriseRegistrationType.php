<?php

namespace App\Form;

use App\Entity\Entreprise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntrepriseRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,['label'=>'Nom du l\'entreprise'])
            ->add('adress', TextType::class,['label' => 'Adresse de l\'entreprise'])
            ->add('description',TextareaType::class,['label'=>'Description de l\'entreprise'])
            ->add('email',EmailType::class,['label'=> 'E-mail'])
            ->add('logoPath', FileType::class, ['label'=>'Logo de l\'entreprise'])
            ->add('password', PasswordType::class, ['label'=>'Mot de passe'])
            ->add('ajouter', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            //
        ]);
    }
}
