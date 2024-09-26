<?php

namespace App\Form;

use App\Entity\Agent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class SearchAgentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('matricule', TextType::class, [
                "label" => "Matricule",
                'constraints' => [
                    new Length([
                        'min' => 7,
                        'max' => 7,
                        'exactMessage' => 'Le champ doit contenir exactement 7 caractères.',
                    ]),
                    new Regex([
                        'pattern' => '/^[A-Za-z]\d{6}$|^\d{6}[A-Za-z]$/',
                        'message' => 'Le matricule entré n\'est pas au format validé.',
                    ]),
                ],
                'attr' => [
                    'placeholder' => 'Saisir le matricule ici. Ex: 999999Z/A000000',
                    'autofocus' => true
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Rechercher',
                'attr' =>['class'=>'btn btn-sm btn-primary'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class' => null,
        ]);
    }
}
