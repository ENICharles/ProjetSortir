<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Localisation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocalisationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('city',EntityType::class,[
                'label'=>'Ville',
                'class' => City::class,
                'choice_label' => 'postcode'
            ])
            ->add('name', TextType::class,[
                'label'=>'Nom du lieu'
            ])
            ->add('street', TextType::class,[
                'label'=>'Rue'
            ])
            ->add('latitude')
            ->add('longitude')

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Localisation::class,
        ]);
    }
}
