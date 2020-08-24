<?php

namespace App\Form;

use App\Entity\PromoCode;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminPromoCodeType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code')
            ->add('expiredAt', TextType::class, $this->getConfiguration("La date d'expiration du code promotion", ['required' => false]))
            ->add('maxNumber', NumberType::class, $this->getConfiguration("Le nombre de fois ou le code promo pourra être utilisé"))
            ->add('type', ChoiceType::class, $this->getConfiguration("Le type de réduction à appliquer",
                [
                    'choices' =>
                        [
                            'FIXE'=> 'FIXE',
                            'POURCENTAGE' => 'POURCENTAGE'
                        ]
                ]))
            ->add('amount', NumberType::class, $this->getConfiguration("Le montant de la réduction"));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PromoCode::class,
            'translation_domain' => 'promoCodeForm'
        ]);
    }
}
