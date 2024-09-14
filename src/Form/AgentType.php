<?php

namespace App\Form;

use App\Entity\Agent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AgentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('matricule', TextType::class, [
                'label' => "Matricule de l'agent",
                'attr' => [
                    'placeholder' => 'Ex: 999999Z/A000000'
                ]
            ])
            ->add('nomAgt', TextType::class, [
                "label" => "Nom de l'agent",
                "attr" => [
                    "placeholder" => "Ex: TCHOS LOLO"
                ]
            ])
            ->add('dateNaisAgt', DateType::class, [
                'label' => "Date de naissance de l'agent",
                'widget' => 'single_text',
            ])
            ->add('dateEmbAgt', DateType::class, [
                'label' => "Date de recrutement de l'agent",
                'widget' => 'single_text',
            ])
            ->add('nb_enft_paye', NumberType::class, [
                'label' => "Nombre d'enfant de l'agent"
            ])
            ->add('Enregistrer', SubmitType::class, [
                'attr' =>['class'=>'btn btn-sm btn-primary'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Agent::class,
        ]);
    }
}
