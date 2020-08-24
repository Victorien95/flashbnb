<?php

namespace App\Controller;

use App\Entity\PromoCode;
use App\Form\AdminPromoCodeType;
use App\Repository\PromoCodeRepository;
use App\Service\Paginator;
use App\Service\TokenError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminPromoCodeController extends AbstractController
{
    /**
     * @Route("/admin/promocode/{page<\d+>?1}", name="admin_promocode_index")
     */
    public function index(Paginator $paginator, $page, PromoCodeRepository $promoCodeRepository)
    {
        $data = $promoCodeRepository->findAll();
        /*$paginator->setEntityClass(PromoCode::class)
            ->setCurrentPage($page)
            ->setLimit(5);
        */

        return $this->render('admin/promocode/index.html.twig', [
            'data' => $data,
            'route' => 'admin_promocode_index'
        ]);
    }

    /**
     * @Route("/admin/promocode/new", name="admin_promocode_new")
     */
    public function create(Request $request, EntityManagerInterface $manager)
    {
        $promocode = new PromoCode();
        $form = $this->createForm(AdminPromoCodeType::class, $promocode);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){

            $manager->persist($promocode);
            $manager->flush();
            $this->addFlash('success', "Le code promo <strong>{$promocode->getCode()}</strong> à bien été enregistrée !");
            return $this->redirectToRoute('admin_promocode_index');
        }

        return $this->render('admin/promocode/new.html.twig',
            [
                'form' => $form->createView()
            ]);


    }

    /**
     * @Route("/admin/promocode/{id}/edit", name="admin_promocode_edit")
     * @param Comment $comment
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param TokenError $tokenError
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function edit(PromoCode $promoCode, \Symfony\Component\HttpFoundation\Request $request, EntityManagerInterface $manager, TokenError $tokenError)
    {

        $tokens = $request->getSession()->all();
        $id = $promoCode->getId();
        if($this->isCsrfTokenValid('edit' . $id, $tokens['_csrf/edit' . $id])){
            $form = $this->createForm(AdminPromoCodeType::class, $promoCode);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()){
                if($this->isCsrfTokenValid('save' . $promoCode->getId(), $request->get('_token_save'))){
                    $manager->persist($promoCode);

                    $manager->flush();

                    $this->addFlash('success', "Le promo code <strong>n°{$promoCode->getId()}</strong> a bien été modifié");
                }

            }

            return $this->render('admin/promocode/edit.html.twig', [
                'form' => $form->createView(),
                'promocode' => $promoCode
            ]);
        }
        $this->addFlash('warning',
            $tokenError->ErrorMessage());
        return $this->redirectToRoute("admin_comment_index");
    }


    /**
     * @Route("admin/promocode/{id}/delete", name="admin_promocode_delete")
     * @param PromoCode $promoCode
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @param TokenError $tokenError
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(PromoCode $promoCode, EntityManagerInterface $manager, Request $request, TokenError $tokenError)
    {
        if ($this->isCsrfTokenValid('delete' . $promoCode->getId(), $request->get('_token'))){
            $id = $promoCode->getId();
            $manager->remove($promoCode);

            $manager->flush();

            $this->addFlash('success', "Le code promo <strong>n°{$id}</strong> a bien été supprimé");
            return $this->redirectToRoute('admin_promocode_index');
        }
        $this->addFlash('warning',
            $tokenError->ErrorMessage());
        return $this->redirectToRoute("admin_promocode_index");

    }
}
