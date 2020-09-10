<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\AdSearch;
use App\Entity\Like;
use App\Entity\Option;
use App\Form\AdSearchType;
use App\Form\AdType;
use App\Repository\AdRepository;
use App\Repository\LikeRepository;
use App\Service\CookieSuggestAd;
use App\Service\Paginator;
use App\Service\Stats;
use App\Service\TokenError;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Snappy\Pdf;
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
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $repository, Request $request, Paginator $paginator, Stats $stats, PaginatorInterface $knp)
    {
        $search = new AdSearch();

        $suggest = $repository->findSuggestQuery($request);

        /**$paginator->setEntityClass(Ad::class)
                  ->setCurrentPage($page)
                  ->setLimit(2);**/

        $form = $this->createForm(AdSearchType::class, $search);
        $form->handleRequest($request);

        $value = $request->query->all();


        /**$paginator = $knp->paginate($repository->findAllVisibleQuery($search),
                $request->query->getInt('page', 1),
                5
            );**/
        //dump($repository->findAllVisibleQuery($search));
        //die();
        $paginator = $knp->paginate($repository->findAllVisibleQuery($search),
            $request->query->getInt('page', 1),
            9
        );

        return $this->render('ad/index.html.twig', [
            'paginator' => $paginator,
            'form' => $form->createView(),
            'stats' => $stats,
            'suggest' => $suggest,
            'value' => $value
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
            $registration = $request->request->all()['ad'];
            if (!$registration['lat'] || !$registration['lng']){
                $this->addFlash('danger', 'Attention adresse non valide veuillez réessayer');
                return $this->redirectToRoute('ads_create');
            }

            foreach ($ad->getImages() as $image) {
                $image->setAd($ad);
                $manager->persist($image);
            }
            $ad->setAuthor($this->getUser())
                ->setUpdatedAt(new \DateTime('now'));
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
    public function edit(Ad $ad, Request $request, EntityManagerInterface $manager, TokenError $tokenError)
    {
        $tokens = $request->getSession()->all();
        $id = $ad->getId();
        if ($this->isCsrfTokenValid('edit' . $id, $tokens['_csrf/edit' . $id])){
            $form = $this->createForm(AdType::class, $ad);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()){

                if ($this->isCsrfTokenValid('save' . $ad->getId(), $request->get('_token_save'))){
                    $ad->setUpdatedAt(new \DateTime('now'));
                    if ($ad->getImages()){
                        foreach ($ad->getImages() as $image) {
                            $image->setAd($ad);
                            $manager->persist($image);
                        }
                    }
                    $manager->persist($ad);
                    $manager->flush();
                    $this->addFlash('success', "Les modifications de l'annonce <strong>{$ad->getTitle()}</strong> ont bien été enregistrée!");
                    return $this->redirectToRoute('ads_show',
                        [
                            'slug' => $ad->getSlug()
                        ]);
                }

            }
            return $this->render('ad/edit.html.twig',
                [
                    'form' => $form->createView(),
                    'ad' => $ad
                ]);
        }
        $this->addFlash('warning',
            $tokenError->ErrorMessage());
        return $this->redirectToRoute('ads_show',
            [
                'ad' => $ad
            ]);

    }

    /**
     * Permet d'afficher une seul annonce
     *
     * @Route("ads/{slug}", name="ads_show", requirements={"slug": "[a-z0-9\-]*"})
     * @return Response
     */
    public function show(Ad $ad, $slug, CookieSuggestAd $suggestAds, Request $request)
    {
        $suggestAds->CookieSuggestSet($request, $ad);
        //$suggestAds->CookieRemove();
        //dump($request->cookies->get('suggest'));
        //die();
       // $suggestAds->CookieSuggestSet($request, $ad);

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
    public function delete(Ad $ad, EntityManagerInterface $manager, Request $request, TokenError $tokenError)
    {
        if ($this->isCsrfTokenValid('delete' . $ad->getSlug(), $request->get('_token'))){
            $manager->remove($ad);
            $manager->flush();
            $this->addFlash('success', "L'annonce <strong>{$ad->getTitle()}</strong> a bien été supprimée !");
            return $this->redirectToRoute("ads_index");
        }
        $this->addFlash('warning',
            $tokenError->ErrorMessage());
        return $this->render('ad/show.html.twig',
            [
                'ad' => $ad
            ]);


    }



    /**
     * @Route("/ads/{slug}/like", name="ads_like")
     * @param Ad $ad
     * @param EntityManagerInterface $manager
     * @param LikeRepository $likeRepository
     */
    public function like(Ad $ad, EntityManagerInterface $manager, LikeRepository $likeRepository)
    {
        $user = $this->getUser();
        if (!$user) return $this->json(['code' => 403, 'message' => 'Non authorisé'], 403);

        if ($ad->isLikeByUser($user)){
            $like = $likeRepository->findOneBy(['ad' => $ad, 'user' => $user]);

            $manager->remove($like);
            $manager->flush();
            return $this->json(['code' => 200, 'message' =>'Bien supprimé','likes'=>$likeRepository->count(['ad' => $ad])], 200);
        }
        $like = new Like();
        $like->setAd($ad)
            ->setUser($user);
        $manager->persist($like);
        $manager->flush();

        return $this->json(['code' => 200, 'message' =>'Like bien ajouté', 'likes'=>$likeRepository->count(['ad' => $ad])], 200);

    }


}
