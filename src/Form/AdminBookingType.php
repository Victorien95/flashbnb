<?php

namespace App\Form;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Entity\User;
use App\Repository\AdRepository;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminBookingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', DateType::class)
            ->add('endDate', DateType::class)
            ->add('comment')
            ->add('booker', EntityType::class, [
                'class' => User::class,
                'query_builder' => function(UserRepository $repo){
                    return $repo->createQueryBuilder('u')
                                ->orderBy('u.lastName', 'ASC');
                },
                'choice_label' => function($user){
                    return strtoupper($user->getLastName()) . " " . $user->getFirstName();
                }
            ])
            ->add('ad', EntityType::class, [
                'class' => Ad::class,
                'query_builder' => function(AdRepository $repo){
                    return $repo->createQueryBuilder('a')
                                ->orderBy('a.id', 'ASC');
                },
                'choice_label' => function(Ad $ad){
                    return $ad->getId() . ' - ' . $ad->getTitle();
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
