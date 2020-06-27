<?php

namespace App\Form;

use App\Entity\Ad;
use App\Entity\AdSearch;
use App\Service\Stats;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
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
