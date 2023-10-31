<?php

namespace App\Form;

use App\Entity\ProfessionalExperience;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProExperienceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('poste', TextType::class,['label'=> 'Poste'])
            ->add('entreprise', TextType::class, ['label'=>'Nom de l\'entreprise'])
            ->add('type',ChoiceType::class, [
                'choices' => [
                    'Type du poste'=>'',
                    'Stage' => 'Stage',
                    'Temps Plein' => 'Temps Plein',
                    'Temps Partiel' => 'Temps Partiel'
                ]]
            )
            ->add('description',TextareaType ::class,['label'=>'Description du poste'])
            ->add('startdate',DateType::class,[
                'widget' => 'single_text',
                'label'=>'Date de début'
            ])
            ->add('enddate',DateType::class,[
                'widget' => 'single_text',
                'label'=> 'Date de fin'
            ])
            ->add('Enregistrer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
          'data_class' => ProfessionalExperience::class,
        ]);
    }
}
