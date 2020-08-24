<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use App\Service\Paginator;
use App\Service\TokenError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfToken;

class AdminAdController extends AbstractController
{
    /**
     * @Route("/admin/ads/{page<\d+>?1}", name="admin_ads_index")
     * <\d+>?1 == requirements={"page" = "\d+"} avec valeur par default 1
     */
    public function index(Paginator $paginator, $page, AdRepository $adRepository)
    {
        $data = $adRepository->findAll();
        /*$paginator->setEntityClass(Ad::class)
                  ->setCurrentPage($page);
        */

        return $this->render('admin/ad/index.html.twig', [
            'data' => $data,
        ]);
    }

    /**
     * Affiche le formulaire d'édition
     *
     * @Route("admin/ads/{id}/edit", name="admin_ads_edit")
     * @param Ad $ad
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Ad $ad, Request $request, EntityManagerInterface $manager, TokenError $tokenError)
    {
        $tokens = $request->getSession()->all();
        $id = $ad->getId();

        if ($this->isCsrfTokenValid('edit' . $id, $tokens['_csrf/edit' . $id])) {
            $form = $this->createForm(AdType::class, $ad);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                /*dump($request->getSession()->all());
                dump($test['_csrf/save' . $id]);
                dump($request->get('_token_save'));*/
                if ($this->isCsrfTokenValid('save' . $ad->getId(), $request->get('_token_save'))) {
                    $ad->setUpdatedAt(new \DateTime('now'));

                    $manager->persist($ad);
                    $manager->flush();

                    $this->addFlash('success', "L'annonce <strong>{$ad->getTitle()}</strong> a bien été enregistrée !");
                }
                return $this->redirectToRoute('admin_ads_edit',
                    [
                        'id' => $id
                    ]);

            }
            return $this->render('admin/ad/edit.html.twig', [
                'ad' => $ad,
                'form' => $form->createView()
            ]);

        }
        $this->addFlash('warning',
            $tokenError->ErrorMessage());
        return $this->redirectToRoute('admin_ads_index');
    }

    /**
     * Supprimer une annonce
     *
     * @Route("admin/ads/{id}/delete", name="admin_ads_delete")
     * @param Ad $ad
     * @param EntityManagerInterface $manager
     */
    public function delete(Ad $ad, EntityManagerInterface $manager, Request $request, TokenError $tokenError)
    {
        if ($this->isCsrfTokenValid('delete' . $ad->getId(), $request->get('_token'))){
            if (count($ad->getBookings()) > 0){
                $this->addFlash('warning',
                    "Vous ne pouvez pas supprimer l'annonce <strong>{$ad->getTitle()}</strong> car elle possède déjà des réservations");
            }else{
                $manager->remove($ad);
                $manager->flush();
                $this->addFlash('success', "L'annonce <strong>{$ad->getTitle()}</strong> a bien été supprimée !");
            }
            return $this->redirectToRoute('admin_ads_index');
        }
        $this->addFlash('warning',
            $tokenError->ErrorMessage());
        return $this->redirect("admin_ads_index");
    }
}
