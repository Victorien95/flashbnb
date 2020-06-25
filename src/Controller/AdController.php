<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\AdSearch;
use App\Form\AdSearchType;
use App\Form\AdType;
use App\Repository\AdRepository;
use App\Service\Paginator;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdController extends AbstractController
{
    /**
     * Index des annonces
     *
     * @Route("/ads/{page<\d+>?1}", name="ads_index")
     */
    public function index(AdRepository $repository, Request $request, Paginator $paginator, $page)
    {
        $search = new AdSearch();

        $paginator->setEntityClass(Ad::class)
                  ->setCurrentPage($page)
                  ->setLimit(9);


        $form = $this->createForm(AdSearchType::class, $search);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()){
            $ads = $repository->findAllVisibleQuery($search);
            $paginator->setQuery($ads);
        }else{
            $paginator->setQuery(null);
        }



        return $this->render('ad/index.html.twig', [
            'paginator' => $paginator,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Permet de creer une annonce
     *
     * @Route("/ads/new", name="ads_create")
     * @IsGranted("ROLE_USER")
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
            $ad->setAuthor($this->getUser());
            $manager->persist($ad);
            $manager->flush();
            $this->addFlash('success', "L'annonce <strong>{$ad->getTitle()}</strong> à bien été enregistrée !");
            return $this->redirectToRoute('ads_show',
                [
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
     * @Security("is_granted('ROLE_USER') and user === ad.getAuthor()", message="Cette annonce ne vous appartient pas, vous ne pouvez pas la modifier")
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
     * @Route("ads/{slug}", name="ads_show", requirements={"slug": "[a-z0-9\-]*"})
     * @return Response
     */
    public function show(Ad $ad, $slug)
    {
        if ($ad->getSlug() != $slug)
        {
            return $this->redirectToRoute('ads_show', [
                'slug' => $ad->getSlug(),
            ]);
        }
        // Récupération de l'annonce en fonction du slug
        // $ad = $adRepository->findOneBySlug($slug);
        return $this->render('ad/show.html.twig',
            [
                'ad' => $ad
            ]);
    }

    /**
     * Permet de supprimer une annonce
     * @Route("ads/{slug}/delete", name="ads_delete", requirements={"slug": "[a-z0-9\-]*"})
     * @Security("is_granted('ROLE_USER') and user === ad.getAuthor()", message="Vous n'avez pas le droit d'accéder à cette ressource")
     *
     * @param Ad $ad
     * @param EntityManagerInterface $manager
     * @return  Response
     */
    public function delete(Ad $ad, EntityManagerInterface $manager)
    {
        $manager->remove($ad);
        $manager->flush();
        $this->addFlash('success', "L'annonce <strong>{$ad->getTitle()}</strong> a bien été supprimée !");
        return $this->redirectToRoute("ads_index");

    }


}
