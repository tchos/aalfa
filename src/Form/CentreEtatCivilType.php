<?php

namespace App\Form;

use App\Entity\CentreEtatCivil;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CentreEtatCivilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('codeCec', TextType::class,[
                'label' => "Code du centre de l'état civil",
                'attr' => [
                    'placeholder' => "Ex: CE4511"
                ]
            ])
            ->add('libelleCec', TextType::class,[
                'label' => "Libellé",
                'attr' => [
                    'placeholder' => "Ex: NOMAYOS I SUD"
                ]
            ])
            ->add('arrondissement', TextType::class,[
                'label' => "Arrondissement du CEC",
                'attr' => [
                    'placeholder' => "Ex: MBANKOMO"
                ]
            ])
            ->add('departement', TextType::class,[
                'label' => "Département du CEC",
                'attr' => [
                    'placeholder' => "Ex: MEFOU ET AKONO"
                ]
            ])
            ->add('region', ChoiceType::class,[
                'label' => "Région du CEC",
                'choices' => [
                    'ADAMAOUA' => "ADAMAOUA",
                    'CENTRE' => "CENTRE",
                    "EST" => "EST",
                    "EXTREME-NORD" => "EXTREME-NORD",
                    "LITTORAL" => "LITTORAL",
                    "NORD" => "NORD",
                    "NORD-OUEST" => "NORD-OUEST",
                    "OUEST" => "OUEST",
                    "SUD" => "SUD",
                    "SUD-OUEST" => "SUD-OUEST",
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CentreEtatCivil::class,
        ]);
    }
}
