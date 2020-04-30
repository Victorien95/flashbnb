<?php

namespace App\Form;

use App\Entity\Ad;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfiguration('Renseignez le titre de votre annonce'))
            ->add('coverImage', UrlType::class, $this->getConfiguration('Donnez l\'adresse d\'une image qui donne vraiment envie'))
            ->add('introduction', TextType::class, $this->getConfiguration('Renseignez votre message de présentation'))
            ->add('content', TextareaType::class, $this->getConfiguration('Renseignez une description détaillée de votre bien'))
            ->add('price', MoneyType::class, $this->getConfiguration('Indiquez le prix pour une nuit'))
            ->add('rooms', IntegerType::class, $this->getConfiguration('Indiquez le nombre de chambres disponible'))
            ->add('images', CollectionType::class,
                [
                    'entry_type' => ImageType::class,
                    'allow_add' => true,
                    'allow_delete' => true
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
            'translation_domain' => 'forms'
        ]);
    }
}
