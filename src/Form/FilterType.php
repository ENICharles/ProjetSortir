<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Filter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
                    'label' => 'Nom de la sortie '
                ]
            )
            ->add('dateStart',
                DateTimeType::class,
                [
                    'label' => 'Entre ',
                    'input_format' => 'd-m-Y H:i:s'
                ]
            )
            ->add('inscriptionDateLimit',
                DateTimeType::class,
                [
                    "label" => 'Et '
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
