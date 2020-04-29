<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $repository)
    {
        $ads = $repository->findAll();
        return $this->render('ad/index.html.twig', [
            'ads' => $ads,
        ]);
    }

    /**
     * Permet de creer une annonce
     * @Route("/ads/new", name="ads_create")
     * @return Response
     */
    public function create()
    {
        $ad = new Ad();
        $form = $this->createForm(AdType::class, $ad);
        return $this->render('ad/new.html.twig',
            [
                'form' => $form->createView()
            ]);

    }
    
    /**
     * Permet d'afficher une seul annonce
     * @Route("ads/{slug}-{id}", name="ads_show", requirements={"slug": "[a-z0-9\-]*"})
     * @return Response
     */
    public function show(Ad $ad, $slug, $id)
    {
        if ($ad->getSlug() != $slug || $ad->getId() != $id)
        {
            return $this->redirectToRoute('ads_show', [
                'slug' => $ad->getSlug(),
                'id' => $ad->getId(),
            ]);
        }
        // Récupération de l'annonce en fonction du slug
        // $ad = $adRepository->findOneBySlug($slug);
        return $this->render('ad/show.html.twig',
            [
                'ad' => $ad
            ]);
    }


}
