<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Image;
use App\Form\AdType;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdController extends AbstractController
{
    /**
     * Index des annonces
     *
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
     *
     * @Route("/ads/new", name="ads_create")
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $manager)
    {
        $ad = new Ad();

        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            foreach ($ad->getImages() as $image) {
                $image->setAd($ad);
                $manager->persist($image);
            }
            $manager->persist($ad);
            $manager->flush();
            $this->addFlash('success', "L'annonce <strong>{$ad->getTitle()}</strong> à bien été enregistrée !");
            return $this->redirectToRoute('ads_show',
                [
                    'id' => $ad->getId(),
                    'slug' => $ad->getSlug()
                ]);
        }
        return $this->render('ad/new.html.twig',
            [
                'form' => $form->createView()
            ]);

    }


    /**
     * Permet d'afficher le formulaire d'édition d'une annonce
     *
     * @Route("ads/edit/{slug}", name="ads_edit")
     * @return Response
     */
    public function edit(Ad $ad, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            foreach ($ad->getImages() as $image) {
                $image->setAd($ad);
                $manager->persist($image);
            }
            $manager->persist($ad);
            $manager->flush();
            $this->addFlash('success', "Les modifications de l'annonce <strong>{$ad->getTitle()}</strong> ont bien été enregistrée!");
            return $this->redirectToRoute('ads_show',
                [
                    'id' => $ad->getId(),
                    'slug' => $ad->getSlug()
                ]);
        }
        return $this->render('ad/edit.html.twig',
            [
                'form' => $form->createView(),
                'ad' => $ad
            ]);
    }

    /**
     * Permet d'afficher une seul annonce
     *
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
