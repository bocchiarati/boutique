<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class RegisterUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                "label" => "Prenom",
                "attr" => [
                    "placeholder" => "Ex : John"
                ]
            ])
            ->add('lastname', TextType::class, [
                "label" => "Nom",
                "attr" => [
                    "placeholder" => "Ex : Doe"
                ]            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse Email',
                "attr" => [
                    "placeholder" => "Ex : johndoe@example.com"
                ],
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'max' => 255
                    ]),
                ]
            ])
            ->add('plainPassword', RepeatedType::class, [
                'label' => 'Mot de passe',
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'Mot de passe',
                    'attr' => [
                        "placeholder" => "Votre Mot de Passe"
                    ],
                    'hash_property_path' => 'password'
                ],
                'second_options' => [
                    'label' => 'Confirmation Mot de passe',
                    'attr' => [
                        "placeholder" => "Votre Mot de Passe (Encore)"
                    ]
                ],
                'mapped' => false
            ])
            ->add("submit", SubmitType::class, [
                'label' => "S'inscrire",
                'attr' => [
                    "class" => "btn btn-primary"
                ],
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'constraints' => [
                new UniqueEntity([
                    'entityClass' => User::class,
                    'fields' => 'email'
                ])
            ],
            'data_class' => User::class,
        ]);
    }
}
