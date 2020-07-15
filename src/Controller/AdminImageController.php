<?php

namespace App\Controller;

use App\Entity\Image;
use App\Service\TokenError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin/image")
 */
class AdminImageController extends AbstractController
{
    /**
     * @Route("/{id}", name="admin_image_delete", methods={"DELETE"})
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
            return $this->redirectToRoute('admin_ads_edit',
                [
                    'id' => $image->getAd()->getId()
                ]);

        }
    }
}
