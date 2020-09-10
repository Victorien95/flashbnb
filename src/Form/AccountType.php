<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, $this->getConfiguration('Votre prénom...'))
            ->add('lastName', TextType::class, $this->getConfiguration('Votre nom de famille...'))
            ->add('email', EmailType::class, $this->getConfiguration('Votre email...'))
            ->add('imageFile', FileType::class,
                [
                    'required' => false,
                    'attr' =>
                        [
                            'placeholder' => 'Uploader votre avatar'
                        ]
                ])
            ->add('introduction', TextType::class, $this->getConfiguration('Présentez vous en quelques mots',
                [
                    'auto_initialize' => false,
                    'attr' =>
                        [
                            'autocomplete' => 'off'
                        ]
                ]))
            ->add('description', TextareaType::class, $this->getConfiguration('Présentez vous en détails',
                [
                    'attr' =>
                        [
                            'rows' => 20
                        ]
                ])
            )

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'translation_domain' => 'registrationForm'
        ]);
    }
}
