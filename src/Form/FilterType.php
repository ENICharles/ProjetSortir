<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Filter;
use EasyCorp\Bundle\EasyAdminBundle\Form\Filter\Type\BooleanFilterType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('campus', EntityType::class,
                [
                    'class' => Campus::class,
                    'choice_label' => 'name',
                ]
            )

            ->add('name',
                TextType::class,
                [
                    'required'=>false,
                    'label' => 'Nom de la sortie '
                ]
            )
            ->add('dateStart',
                DateTimeType::class,
                [
                    'required'=>false,
                    'label' => 'Entre ',
                    'html5' => true,
                    'widget' => 'single_text',
                    'input_format' => 'd-m-Y H:i:s'

                ]
            )
            ->add('inscriptionDateLimit',
                DateTimeType::class,
                [
                    'required'=>false,
                    "label" => 'Et ',
                    'widget' => 'single_text',
                    'input_format' => 'd-m-Y H:i:s'
                ]
            )

            ->add( 'isOrganisator',
                CheckboxType::class,
                [
                    'label' => 'Sorties dont je suis l\'organisateur',
                    'required' => false,
                ]

            )
        ;
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Filter::class,
        ]);
    }
}
