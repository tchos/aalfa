<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UpdateProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => "Email",
                'disabled' => true
            ])
            ->add('fullname', TextType::class, [
                'label' => "Nom complet",
                'constraints' => [
                    new NotBlank([
                        'message' => "Le nom complet ne peut être vide ",
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Au moins {{ limit }} caractères pour le nom !!!',
                        // max length allowed by Symfony for security reasons
                        'max' => 32,
                    ]),
                ],
                'attr' => [
                    'placeholder' => "Ex: TUCKO BENEDICTO PACIFICO RUAN MARIA RAMIREZ",
                ]
            ])
            ->add('telephone', TextType::class, [
                'label' => "Téléphone",
                'constraints' => [
                    new NotBlank([
                        'message' => "Le téléphone ne peut être vide ",
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Téléphone doit avoir au moins {{ limit }} caractères !!!',
                        // max length allowed by Symfony for security reasons
                        'max' => 32,
                    ]),
                ],
                'attr' => [
                    'placeholder' => "Ex: 699887766 / 677665544 / 622110000",
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
