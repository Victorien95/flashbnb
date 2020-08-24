<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('email')
            ->add('imageFile', FileType::class,
                [
                    'required' => false,
                    'attr' =>
                        [
                            'placeholder' => 'Uploader votre avatar'
                        ]
                ])
            ->add('introduction')
            ->add('description', TextareaType::class,
                [
                    'attr' =>
                        [
                            'rows' => 20
                        ]
                ])
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
