<?php

namespace App\Controller;

use App\Entity\Newsletter;
use App\Form\NewsletterEmailType;
use App\Form\NewsletterType;
use App\Repository\NewsletterRepository;
use App\Service\MailerService;
use App\Service\TokenError;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminNewsletterController
 * @package App\Controller
 * @Route("/admin/newsletter")
 */
class AdminNewsletterController extends AbstractController
{
    /**
     * @Route("/", name="admin_newsletter_index")
     */
    public function index(NewsletterRepository $newsletterRepository)
    {
        $data = $newsletterRepository->findAll();


        return $this->render('admin/newsletter/index.html.twig', [
            'data' => $data
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_newsletter_edit", methods={"GET","POST", "EDIT"})
     */
    public function edit(Request $request, Newsletter $newsletter, TokenError $tokenError): Response
    {
        $tokens = $request->getSession()->all();
        $id = $newsletter->getId();

        if ($this->isCsrfTokenValid('edit'. $id, $tokens['_csrf/edit' . $id])){
            $form = $this->createForm(NewsletterType::class, $newsletter);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                if ($this->isCsrfTokenValid('save' . $newsletter->getId(), $request->get('_token_save'))){
                    $this->getDoctrine()->getManager()->flush();

                    return $this->redirectToRoute('admin_newsletter_index');
                }

            }
            return $this->render('admin/newsletter/edit.html.twig', [
                'newsletter' => $newsletter,
                'form' => $form->createView(),
            ]);
        }
        $this->addFlash('warning',
            $tokenError->ErrorMessage());
        return $this->redirectToRoute("admin_newsletter_index");

    }

    /**
     * @Route("/{id}/delete", name="admin_newsletter_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Newsletter $newsletter, TokenError $tokenError): Response
    {
        if ($this->isCsrfTokenValid('delete'. $newsletter->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($newsletter);
            $entityManager->flush();
            return $this->redirectToRoute('admin_newsletter_index');
        }

        $this->addFlash('warning',
            $tokenError->ErrorMessage());
        return $this->redirectToRoute('admin_newsletter_index');

    }

    /**
     * @Route("/email", name="admin_newsletter_email")
     */
    public function email(Request $request, MailerService $mailerService, NewsletterRepository $repository)
    {

        $form = $this->createForm(NewsletterEmailType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
           $mailerService->newsletter($form->getData()['subject'], $form->getData()['body'], $repository);

           $this->addFlash('success', 'La newsletter à bien été envoyé !');

           return $this->redirectToRoute('admin_newsletter_index');
        }

        return $this->render('admin/newsletter/email.html.twig',
            [
                'form' => $form->createView()
            ]);

    }

}
