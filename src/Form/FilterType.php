<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Filter;
use EasyCorp\Bundle\EasyAdminBundle\Form\Filter\Type\BooleanFilterType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Entity\Product;

class FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('campus', EntityType::class,
                [
                    'class' => Campus::class,
                    'choice_label' => 'name',
                    'label_attr' => ['class' => 'form__label'],
                    'attr' => ["class" => "form__field"]

                ]
            )

            ->add('name',
                TextType::class,
                [
                    'label' => 'Nom de la sortie ',
                    'attr' => ["class" => "form__field"],
                    'required'=>false
                ]
            )
            ->add('dateStart',
                DateTimeType::class,
                [
                    'label' => 'Entre ',
                    'widget' => 'single_text',
                    'input_format' => 'd-m-Y H:i:s',
                    'attr' => ["class" => "form__field"],
                    'data' => new \DateTime("now")
                ]
            )
            ->add('dateEnd',
                DateTimeType::class,
                [
                    'required'=>false,
                    "label" => 'Et ',
                    'widget' => 'single_text',
                    'input_format' => 'd-m-Y H:i:s',
                    'data' => (new \DateTime("now"))->modify('+1 day')
                ]
            )

            ->add( 'isOrganisator',
                CheckboxType::class,
                [
                    'label' => "Sorties dont je suis l'organisateur",
                    'attr' => ["class" => "form__field"],
                    'required' => false,
                ]
            )

            ->add('isRegistered',
                    CheckboxType::class,
                [
                    'label' => "Sorties auxquelles je suis inscrit/e",
                    'attr' => ["class" => "form__field"],
                    'required' => false,
                ]
            )

            ->add('isNotRegistered',
                CheckboxType::class,
                [
                    'label' => "Sorties auxquelles je ne suis pas inscrit/e",
                    'attr' => ["class" => "form__field"],
                    'required' => false,
                ]
            )
            ->add('isPassedEvent',
                CheckboxType::class,
                [
                    'label' => "Sorties passées ",
                    'attr' => ["class" => "form__field"],
                    'required' => false
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
