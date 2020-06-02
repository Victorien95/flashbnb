<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminUserType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, $this->getConfiguration("Prénom de l'utilisateur"))
            ->add('lastName', TextType::class, $this->getConfiguration("Nom de l'utilisateur"))
            ->add('email', EmailType::class, $this->getConfiguration("Email de l'utilisateur"))
            ->add('picture', UrlType::class, $this->getConfiguration("Modifier l'image de l'utilisateur"))
            ->add('introduction', TextType::class, $this->getConfiguration("Corriger l'introduction de l'utilisateur"))
            ->add('description', TextareaType::class, $this->getConfiguration("Corriger la description de l'utilisateur"))
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
