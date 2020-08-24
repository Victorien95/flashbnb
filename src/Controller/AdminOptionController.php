<?php

namespace App\Controller;

use App\Entity\Option;
use App\Form\OptionType;
use App\Repository\OptionRepository;
use App\Service\Paginator;
use App\Service\TokenError;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/option")
 */
class AdminOptionController extends AbstractController
{
    /**
     * @Route("/{page<\d+>?1}", name="admin_option_index", methods={"GET"})
     */
    public function index(Paginator $paginator, $page, OptionRepository $optionRepository): Response
    {
        $data = $optionRepository->findAll();

        /*$paginator->setEntityClass(Option::class)
            ->setCurrentPage($page)
            ->setLimit(5);
        */

        return $this->render('admin/option/index.html.twig', [
            'data' => $data
        ]);
    }

    /**
     * @Route("/new", name="admin_option_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $option = new Option();
        $form = $this->createForm(OptionType::class, $option);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($option);
            $entityManager->flush();

            return $this->redirectToRoute('admin_option_index');
        }

        return $this->render('admin/option/new.html.twig', [
            'option' => $option,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}/edit", name="admin_option_edit", methods={"GET","POST", "EDIT"})
     */
    public function edit(Request $request, Option $option, TokenError $tokenError): Response
    {
        $tokens = $request->getSession()->all();
        $id = $option->getId();

        if ($this->isCsrfTokenValid('edit'. $id, $tokens['_csrf/edit' . $id])){
            $form = $this->createForm(OptionType::class, $option);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                if ($this->isCsrfTokenValid('save' . $option->getId(), $request->get('_token_save'))){
                    $this->getDoctrine()->getManager()->flush();

                    return $this->redirectToRoute('admin_option_index');
                }

            }
            return $this->render('admin/option/edit.html.twig', [
                'option' => $option,
                'form' => $form->createView(),
            ]);
        }
        $this->addFlash('warning',
            $tokenError->ErrorMessage());
        return $this->redirectToRoute("admin_option_index");

    }

    /**
     * @Route("/{id}/delete", name="admin_option_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Option $option, TokenError $tokenError): Response
    {
        if ($this->isCsrfTokenValid('delete'. $option->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($option);
            $entityManager->flush();
            return $this->redirectToRoute('admin_option_index');
        }

        $this->addFlash('warning',
            $tokenError->ErrorMessage());
        return $this->redirectToRoute('admin_option_index');

    }
}
