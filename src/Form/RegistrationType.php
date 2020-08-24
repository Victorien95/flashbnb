<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends ApplicationType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
            ->add('firstName', TextType::class, $this->getConfiguration('Votre prénom...'))
            ->add('lastName', TextType::class, $this->getConfiguration('Votre nom de famille...'))
            ->add('email', EmailType::class, $this->getConfiguration('Votre email'))
            //->add('picture', UrlType::class, $this->getConfiguration('URL de votre avatar'))
            ->add('imageFile', FileType::class,
                [
                    'required' => false,
                    'attr' =>
                        [
                            'placeholder' => 'Uploader votre avatar'
                        ]
                ])
            ->add('hash', PasswordType::class, $this->getConfiguration('Choisissez votre mot de passe'))
            ->add('passwordConfirm', PasswordType::class, $this->getConfiguration('Veuillez confirmer votre mot de passe'))
            ->add('introduction', TextType::class, $this->getConfiguration('Présentez vous en quelques mots'))
            ->add('description', TextareaType::class, $this->getConfiguration('Présentez vous en détails'))
            ->add('newsletter', CheckboxType::class, $this->getConfiguration("S'inscrie à la newsletter",
                [
                    'label' => "S'inscrire à la newsletter",
                    'mapped' => false,
                    'required' => false,
                    'attr' =>
                        [
                            'checked' => true
                        ]
                ]))
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
