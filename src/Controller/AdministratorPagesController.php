<?php

namespace App\Controller;

use App\Entity\Newsletter;
use App\Form\ContactType;
use App\Form\NewsletterType;
use App\Repository\NewsletterRepository;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdministratorPagesController extends AbstractController
{
    /**
     * @Route("/qsm", name="administrator_pages_qsm")
     */
    public function qsm()
    {
        return $this->render('administrator_pages/qsm.html.twig', [
        ]);
    }

    /**
     * @Route("/conditions-generales-des-ventes", name="administrator_pages_cgv")
     */
    public function cgv()
    {
        return $this->render('administrator_pages/cgv.html.twig', [
        ]);
    }

    /**
     * @Route("/informations-flashbnb", name="administrator_pages_infoFlashbnb")
     */
    public function infoFlashbnb()
    {
        return $this->render('administrator_pages/informationsFlashbnb.html.twig', [
        ]);
    }

    /**
     * @Route("/mentions-legales", name="administrator_pages_mentionsLegales")
     */
    public function mentionsLegales()
    {
        return $this->render('administrator_pages/mentionsLegales.html.twig', [
        ]);
    }

    /**
     * @Route("/plan-du-site-flashbnb", name="administrator_pages_plan")
     */
    public function plan()
    {
        return $this->render('administrator_pages/plan.html.twig', [
        ]);
    }

    /**
     * @Route("/newsletter", name="administrator_pages_newsletter")
     */
    public function newsletter(Request $request, NewsletterRepository $newsletterRepository, EntityManagerInterface $manager)
    {
        $newsletter = new Newsletter();
        $form = $this->createForm(NewsletterType::class, $newsletter);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $data = $form->getData()->getEmail();
            if (!$newsletterRepository->findOneBy(['email' => $data])){
                $manager->persist($newsletter);
                $manager->flush();
                $this->addFlash('success', 'Votre inscription à la newsletter a bien été prise en compte !');
                return $this->redirectToRoute('ads_index');
            }else{
                $this->addFlash('danger', "Votre inscription à la newsletter na pas pu être prise en compte ! <br> Votre adresse email est déjà utilisée");
                return $this->redirectToRoute('administrator_pages_newsletter');
            }
        }
        return $this->render('administrator_pages/newsletter.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @Route("/contact", name="administrator_pages_contact")
     * @IsGranted("ROLE_USER")
     */
    public function contact(Request $request, MailerService $mailerService)
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $mailerService->contact($this->getUser(), $form->get('subject')->getData(), $form->get('text')->getData());


            $this->addFlash('success', 'Votre message a bien été envoyé !');

            return $this->redirectToRoute('user_show',
                [
                    'slug' => $this->getUser()->getSlug()
                ]);
        }
        return $this->render('administrator_pages/contact.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
