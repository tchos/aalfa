<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UpdatePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullname', TextType::class, [
                'label' => "Nom complet",
                'disabled' => true
            ])
            ->add('email', EmailType::class, [
                'label' => "Email",
                'disabled' => true
            ])
            ->add('telephone', TextType::class, [
                'label' => "Téléphone",
                'disabled' => true
            ])
            ->add('old_password', PasswordType::class, [
                'label' => "Saisissez votre ancien mot de passe",
                'mapped' => false,
                'required' => true
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passes ne correspondent',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => [
                    'label' => 'Mot de passe',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Le mot de passe ne doit pas être vide',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Le password doit avoir au moins {{ limit }} caractères !!!',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                    ],
                ],
                'second_options' => [
                    'label' => 'Confirmer le mot de passe',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Le mot de passe ne doit pas être vide',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Le password doit avoir au moins {{ limit }} caractères !!!',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                    ],
                ],
                'attr' => ['autocomplete' => 'new-password'],
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
