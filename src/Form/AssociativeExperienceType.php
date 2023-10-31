<?php

namespace App\Form;

use App\Entity\AssociativeExperience;
use App\Entity\CurriculumVitae;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class AssociativeExperienceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('organization', TextType::class,['label'=>'Organisation'])
            ->add('description',TextareaType ::class,['label'=>'Description'])
            ->add('startdate',DateType::class,[
                'widget' => 'single_text',
                'label'=> 'Date de début'
            ])
            ->add('enddate',DateType::class,[
                'widget' => 'single_text',
                'label' => 'Date de fin'
            ])
            ->add('Enregistrer',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AssociativeExperience::class,
        ]);
    }
}
