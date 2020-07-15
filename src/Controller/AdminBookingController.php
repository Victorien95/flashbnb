<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Service\Paginator;
use App\Service\TokenError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminBookingController extends AbstractController
{
    /**
     * @Route("/admin/booking/{page<\d+>?1}", name="admin_booking_index")
     */
    public function index(Paginator $paginator, $page)
    {
        $paginator->setEntityClass(Booking::class)
                  ->setCurrentPage($page);



        return $this->render('admin/booking/index.html.twig', [
            'pagination' => $paginator
        ]);
    }


    /**
     * Edition d'une réservation
     *
     * @Route("admin/booking/{id}/edit", name="admin_booking_edit")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Booking $booking, Request $request, EntityManagerInterface $manager, TokenError $tokenError)
    {
        $tokens = $request->getSession()->all();
        $id = $booking->getId();

        if($this->isCsrfTokenValid('edit' . $id, $tokens['_csrf/edit' . $id])){
            $form = $this->createForm(AdminBookingType::class, $booking);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()){
                if($this->isCsrfTokenValid('save' . $booking->getId(), $request->get('_token_save'))){
                    $booking->setAmount(0);
                    $manager->persist($booking);
                    $manager->flush();
                    $this->addFlash('success', "La réservation <strong>n°{$booking->getId()} a bien été modifiée</strong>");

                }
            }

            return $this->render('admin/booking/edit.html.twig', [
                'form' => $form->createView(),
                'booking' => $booking

            ]);
        }
        $this->addFlash('warning',
            $tokenError->ErrorMessage());
        return $this->redirectToRoute("admin_booking_index");

    }

    /**
     * Supprimer une réservation
     *
     * @Route("admin/booking/{id}/delete", name="admin_booking_delete")
     * @param Booking $booking
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Booking $booking, EntityManagerInterface $manager, Request $request, TokenError $tokenError)
    {
        if ($this->isCsrfTokenValid('delete' . $booking->getId(), $request->get('_token'))){
            $bookingId = $booking->getId();
            $manager->remove($booking);
            $manager->flush();

            $this->addFlash('success', "La réservation <strong>n°{$bookingId} a bien été supprimée</strong>");

            return $this->redirectToRoute('admin_booking_index');
        }
        $this->addFlash('warning',
            $tokenError->ErrorMessage());
        return $this->redirectToRoute("admin_booking_index");

    }
}
