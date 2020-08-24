<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Image;
use App\Service\TokenError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImageController extends AbstractController
{

    /**
     * @Route("/ads/image/{id}", name="image_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Image $image, EntityManagerInterface $manager, TokenError $tokenError): Response
    {
        $data = json_decode($request->getContent(), true);
        if ($this->isCsrfTokenValid('delete'. $image->getId(), $data['_token'])) {
            $manager->remove($image);
            $manager->flush();
            return new JsonResponse(['success' => 1]);
        }else{
            $this->addFlash('warning',
                $tokenError->ErrorMessage());
            return $this->redirectToRoute('ads_edit',
                [
                    'id' => $image->getAd()->getId()
                ]);

        }
    }

    /**
     * @Route("/ads/image/cover/{id}", name="image_delete_cover", methods={"DELETE"})
     */
    public function delete_cover(Request $request, Ad $ad, EntityManagerInterface $manager, TokenError $tokenError): Response
    {

        $data = json_decode($request->getContent(), true);
        if ($this->isCsrfTokenValid('delete'. $ad->getId(), $data['_token'])) {

            if ($ad->getAdCoverImage()){
                $ad->setAdCoverImage(null);
            }else{
                $ad->setCoverImage(null);
            }
            $manager->flush();
            return new JsonResponse(['success' => 1]);
        }else{
            $this->addFlash('warning',
                $tokenError->ErrorMessage());
            return $this->redirectToRoute('ads_edit',
                [
                    'id' => $ad->getId()
                ]);

        }
    }
}
