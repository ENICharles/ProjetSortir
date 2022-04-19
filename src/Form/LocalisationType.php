<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Localisation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocalisationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
//Todo / formulaire de localisation affiche une donnée en dur
        $builder
            ->add('city',CityType::class,[
                'label'=>false
                ]
            )

            ->add('name', null,[
                'label'=>'Nom du lieu'

            ])
            ->add('street', null,[
                'label'=>'Rue'

            ])
            ->add('latitude',TextType::class)
            ->add('longitude',TextType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Localisation::class,
        ]);
    }
}
