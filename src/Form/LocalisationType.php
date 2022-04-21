<?php

namespace App\Form;

use App\Entity\Localisation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class LocalisationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('city',CityType::class,[
                'label'=>false
                ]
            )
            ->add('name', null,[
                'label'=>'Nom du lieu',
            ])
            ->add('street', null,[
                'label'=>'Rue',
                'disabled'=> true
            ])
            ->add('latitude',TextType::class,[
                'disabled'=> true
            ])
            ->add('longitude',TextType::class,[
                'disabled'=> true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Localisation::class,
        ]);
    }
}
