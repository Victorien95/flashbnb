<?php

namespace App\Form;

use App\Entity\Ad;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdType extends AbstractType
{
    /**
     * Permet d'avoir la configuration de base d'un champ
     * @param  $label
     * @param $placeholder
     * @return array
     */
    private function getConfiguration($label, $placeholder){
        return [
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
        ];
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfiguration('Titre', 'Tapez votre titre'))
            ->add('slug', TextType::class, $this->getConfiguration('Message d\'introduction', 'Tapez votre message de présentation'))
            ->add('coverImage', UrlType::class, $this->getConfiguration('Url de l\'image principale', 'Donnez l\'adresse d\'une image qui donne vraiment envie'))
            ->add('introduction', TextType::class, $this->getConfiguration('Message d\'introduction', 'Tapez votre message de présentation'))
            ->add('content', TextareaType::class, $this->getConfiguration('Description détaillée', 'Tapez votre message de présentation'))
            ->add('price', MoneyType::class, $this->getConfiguration('Prix par nuit', 'Indiquez le prix pour une nuit'))
            ->add('rooms', IntegerType::class, $this->getConfiguration('Nombre de chambres', 'Le nombre de chambres disponible'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
