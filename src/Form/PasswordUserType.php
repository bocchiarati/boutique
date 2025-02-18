<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PasswordUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('actualPassword', PasswordType::class, [
                'label' => 'Mot de passe Actuel',
                'attr' => [
                    'placeholder' => 'Votre mot de passe actuel'
                ],
                'mapped' => false
            ])
            ->add('plainPassword', RepeatedType::class, [
                'label' => 'Mot de passe',
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'Nouveau Mot de passe',
                    'attr' => [
                        "placeholder" => "Votre Nouveau Mot de Passe"
                    ],
                    'hash_property_path' => 'password'
                ],
                'second_options' => [
                    'label' => 'Confirmation Mot de passe',
                    'attr' => [
                        "placeholder" => "Votre Nouveau Mot de Passe (Encore)"
                    ]
                ],
                'mapped' => false
            ])
            ->add("submit", SubmitType::class, [
                'label' => "Effectuer la modification",
                'attr' => [
                    "class" => "btn btn-primary"
                ],
            ])
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
                $form = $event->getForm();
                $user  = $form->getConfig()->getOptions()['data'];
                $passwordHasher = $form->getConfig()->getOptions()['passwordHasher'];

                $isValid = $passwordHasher->isPasswordValid(
                    $user,
                    $form->get("actualPassword")->getData()
                );

                if (!$isValid) {
                    $form->get("actualPassword")->addError(new FormError("Mot de passe non conforme"));
                }
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'passwordHasher' => null,
        ]);
    }
}
