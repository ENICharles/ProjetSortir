<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username',TextType::class,[
                'label'=>'Pseudo',
                "attr" => ['class' => "user"]
            ])
            ->add('name',TextType::class,[
                'label'=>'Nom : ',
                "attr" => ['class' => "user"]

            ])
            ->add('firstname',TextType::class,[
                'label'=>'Prénom : ',
                "attr" => ['class' => "user"]

            ])
            ->add('email',TextType::class,[
                'label'=>'Email : ',
                "attr" => ['class' => "user"]

            ])
            ->add('password',RepeatedType::class,[
                'type'=> PasswordType::class,
                'invalid_message' => 'Le mot de passe doit être identique.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Mot de Passe : '],
                'second_options' => ['label' => 'Confirmation : '],
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez rentrer un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au moins 6 caractères',
                        'max' => 15,
                    ]),
                ]

            ])
            ->add('phone',TextType::class,[
                'label'=>'Téléphone : ',
                "attr" => ['class' => "user"]

            ])
            ->add('campus', EntityType::class,[
               'class'=>Campus::class,
               'choice_label' => 'name',
                "attr" => ['class' => "user"]
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
