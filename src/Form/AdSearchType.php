<?php

namespace App\Form;

use App\Entity\Ad;
use App\Entity\AdSearch;
use App\Entity\Option;
use App\Service\Stats;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdSearchType extends ApplicationType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder
            ->add('maxPrice', IntegerType::class, $this->getConfiguration('Prix / nuits maximum',
                [
                    'required' => false,
                    'label' => 'Prix / nuits'
                ]))
            ->add('minRooms', IntegerType::class, $this->getConfiguration('Nombre de chambres minimum',
                [
                    'required' => false,
                    'label' => 'Chambres',
                    'attr' =>
                        [
                            'min' => 1
                        ]
                ]))
            ->add('distance', RangeType::class, $this->getConfiguration('Distance maximum', [
                'attr' =>
                    [
                        'max' => 100,
                        'min' => 10,
                        'step' => 10
                    ]
            ]))
            ->add('lat', HiddenType::class)
            ->add('lng', HiddenType::class)
            ->add('options', EntityType::class, $this->getConfiguration('Choisissez vos options',
                [
                    'class' => Option::class,
                    'choice_label' => 'name',
                    'multiple' => true,
                    'required' => false
                ]))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AdSearch::class,
            'method' => 'get',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return "";
    }






}
