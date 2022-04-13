<?php

namespace App\Form;


use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Event;
use App\Entity\Localisation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',
                TextType::class,
                [
                    "label" => 'Nom de l\'évènement ',
                    "attr" => ["class" => 'event__name']
                ]
            )

            ->add('campus',
                EntityType::class,
                [
                    "label" => 'Campus ',
                    'class' => Campus::class,
                    'choice_label' => "name"
                ]
            )

            ->add('dateStart',
                DateTimeType::class,
                [
                    "label" => 'Date et heure de la sortie',
                    'date_widget' => 'single_text',
                ]
            )

            ->add('inscriptionDateLimit',
                DateTimeType::class,
                [
                    "label" => 'Date limite d\'inscription',
                    'date_widget' => 'single_text',
                ]
            )

            ->add('duration',
                IntegerType::class,
                [
                    "label" => 'Durée ',
                    "attr" =>
                        [
                            "class" => 'duration',
                            "step" => 1,
                        ]
                ]
            )

            ->add('nbMaxInscription',
                IntegerType::class,
                [
                    "label" => 'Nombre de places ',
                    "attr" =>
                        [
                            "class" => 'event__nbMaxInscription',
                            "step" => 1
                        ]
                ]
            )

            ->add('description',
                TextareaType::class,
                [
                    "label" => 'Description et infos ',
                    "attr" => ["class" => 'event__description',]
                ]
            )

            ->add('localisation',
                ChoiceType::class,
                [
                    "label" => 'Lieu '
                ]
            )

            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
