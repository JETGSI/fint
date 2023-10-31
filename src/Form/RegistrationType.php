<?php

namespace App\Form;

use App\Entity\Student;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName',
                TextType::class,
                [ 'label' => 'Prénom' ])
            ->add('lastName',
                TextType::class,
                [ 'label' => 'Nom du famille' ])
            ->add('email',
                EmailType::class,
                [ 'label' => 'E-mail' ])
            ->add('telephone',TextType::class,[
                'label'=>'Numéro de Téléphone'
            ])
            ->add('password',
                PasswordType::class,
                [ 'label' => 'Mot de passe' ])
            ->add('address',
                TextType::class,
                [ 'label' => 'Adresse' ])
            ->add('linkedinLink',
                TextType::class,
                ['label' => "Votre lien linkedin"] )
            ->add('img',
                FileType::class,
                [ 'label' => 'Image du profil' ])
            ->add('je',
                CheckboxType::class, [
                'label'    => 'Vous êtes un membre d\'une Junior Enterprise',
                'required' => false,
            ])

            ->add('sharedata',
                CheckboxType::class, [
                    'label'    => 'Accepter vous la partage de vos données avec nos partenaires ?',
                    'required' => false,
                ])
            ->add('Sinscrire', SubmitType::class, [ 'label' => 'S\'inscrire' ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // 'data_class'=> Student::class
        ]);
    }
}
