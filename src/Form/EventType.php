<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Event;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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
            ->add('name', TextType::class,[
                'label'=>'Nom :'
            ])

            ->add('dateStart', DateTimeType::class,[
                'label' => 'Date de début :',
                'widget'=> 'single_text',
                'input_format' => 'd-m-Y H:i:s'

            ])

            ->add('inscriptionDateLimit',DateTimeType::class,[
                'label' => 'Date de limite d\'inscription :',
                'widget'=> 'single_text',
                'input_format' => 'd-m-Y H:i:s'
            ])

            ->add('nbMaxInscription', IntegerType::class,[
                'label'=>'Nombre de places',
                'attr'=> ['min'=>0]
            ])

            ->add('duration', IntegerType::class,[
                'label'=>'Durée'
//                'attr'=>[
//                    'class'=> [
//                        'unit'=>'minutes'
//                    ]
//    ]
            ])

            ->add('description', TextareaType::class)

            ->add('campus',EntityType::class,[
                'class'=> Campus::class,
                'choice_label'=> 'name'
            ])

            ->add('localisation',LocalisationType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
