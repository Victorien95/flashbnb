<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('subject', TextType::class, $this->getConfiguration('Sujet de votre demande',
                [
                    'label' => 'Sujet',
                    'required' => true,
                    'attr' =>
                        [
                            'minLength' => 10
                        ]
                ]))
            ->add('text', TextareaType::class, $this->getConfiguration('Votre demande',
                [
                    'label' => 'Votre demande',
                    'required' => true,
                    'attr' =>
                        [
                            'minLength' => 100
                        ]
                ]))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
