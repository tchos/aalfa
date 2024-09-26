<?php

namespace App\Form;

use App\Entity\Agent;
use App\Entity\Enfant;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EnfantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_enfant', TextType::class,[
                'label' => 'Nom de l\'enfant',
            ])
            ->add('date_naissance', DateType::class, [
                'label' => 'Date de naissance',
                'widget' => 'single_text',
            ])
            ->add('cec', TextType::class, [
                'label' => 'Code CEC',
                'attr' => [
                    'placeholder' => 'Ex: C162',
                ]
            ])
            ->add('codeArrondissement', TextType::class, [
                'label' => 'Code arrondissement du CEC',
                'attr' => [
                    'placeholder' => 'Ex: A093',
                ]
            ])
            ->add('numero_acte', TextType::class, [
                'label' => 'Numéro Acte de naissance',
                'attr' => [
                    'placeholder' => 'Ex: 2711/58',
                ]
            ])
            ->add('date_acte_naissance', DateType::class, [
                'label' => 'Date de délivrance de l\'acte de naissance',
                'widget' => 'single_text',
            ])
            ->add('nom_conjoint', TextType::class, [
                'label' => 'Nom du conjoint',
                'attr' => [
                    'placeholder' => 'Ex: DIOS BENEDICTO PACIFICO',
                ]
            ])
            ->add('Enregistrer', SubmitType::class, [
                'attr' =>['class'=>'btn btn-sm btn-success'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Enfant::class,
        ]);
    }
}
