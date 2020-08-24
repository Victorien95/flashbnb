<?php


namespace App\Service;


use App\Entity\PromoCode;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Session\Session;

class PromoCodeChecker
{
    public function PromoChecker(PromoCode $promoCode, User $user)
    {
        $today = new \DateTime('now');
        $session = new Session();

        if ($promoCode->getExpiredAt() && $promoCode->getExpiredAt() < $today){
            $session->getFlashBag()->add('danger', 'Désolé, le code de promotion est expiré');
            return false;
        }
        if ($promoCode->getMaxNumber() && $promoCode->getMaxNumber() === -1){
            $session->getFlashBag()->add('danger', "Désolé, ce code promotion n'est plus utilisable");
            return false;
        }
        if ($promoCode->getUser() && $promoCode->getUser() !== $user){
            $session->getFlashBag()->add('danger', "Désolé, ce code promotion ne peut pas vous être attribué");
            return false;
        }
        if ($promoCode->getMaxNumber() !== null && $promoCode->getMaxNumber() > 1){
            $promoCode->setMaxNumber($promoCode->getMaxNumber() - 1);
        }
        if ($promoCode->getMaxNumber() !== null && $promoCode->getMaxNumber() === 1){
            $promoCode->setMaxNumber(-1);
        }
        return true;
    }

}