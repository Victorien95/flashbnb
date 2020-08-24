<?php

namespace App\Controller;

use App\Entity\PromoCode;
use App\Repository\PromoCodeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class PromoCodeController extends AbstractController
{
    /**
     * @Route("/promocode/checker", name="promocode_checker")
     */
    public function promocodeChecker(PromoCodeRepository $promoCodeRepository)
    {
        //$response->headers->set('Access-Control-Allow-Origin', 'Allow');

        $promoCodeChecker = $promoCodeRepository->findAll();

        return $this->json(['code' => 200, 'message' =>'demande de promo code','promoCodes'=> $promoCodeChecker], 200, [],
            [
                //ObjectNormalizer::ENABLE_MAX_DEPTH => true,
                //AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
                //ObjectNormalizer::CIRCULAR_REFERENCE_LIMIT => 5,
                ObjectNormalizer::IGNORED_ATTRIBUTES => ['user'],
                ObjectNormalizer::GROUPS => ['promo']
            ]);

    }
}
