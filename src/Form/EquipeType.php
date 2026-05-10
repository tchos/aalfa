<?php

namespace App\Form;

use App\Entity\Equipe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EquipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle', TextType::class, [
                'label' => 'Libellé',
                'attr' => [
                    'placeholder' => 'EX: CENTRE',
                    'autofocus' => true,
                ]
            ])
            ->add('code', TextType::class, [
                'label' => 'Code',
                'attr' => [
                    'placeholder' => 'EX: CE01',
                ]
            ])
            ->add('chef', TextType::class, [
                'label' => 'Chef d\'équipe',
                'attr' => [
                    'placeholder' => 'EX: TCHOS POPOL',
                ]
            ])
            ->add('coordonnateur', TextType::class, [
                'label' => 'Nom du Coordonnateur',
                'attr' => [
                    'placeholder' => 'EX: AALFA COORDONATOR',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Equipe::class,
        ]);
    }
}
