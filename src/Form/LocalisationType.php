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
                'label'=>'Rue'
            ])
            ->add('latitude',TextType::class,[
                'required'=>false
            ])
            ->add('longitude',TextType::class,[
                'required'=>false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Localisation::class,
        ]);
    }
}
