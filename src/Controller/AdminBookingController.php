<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Service\Paginator;
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
    public function edit(Booking $booking, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(AdminBookingType::class, $booking);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()){
            $booking->setAmount(0);

            $manager->persist($booking);

            $manager->flush();
            
            $this->addFlash('success', "La réservation <strong>n°{$booking->getId()} a bien été modifiée</strong>");

            return $this->redirectToRoute('admin_booking_index');
        }

        return $this->render('admin/booking/edit.html.twig', [
            'form' => $form->createView(),
            'booking' => $booking

        ]);
    }

    /**
     * Supprimer une réservation
     *
     * @Route("admin/booking/{id}/delete", name="admin_booking_delete")
     * @param Booking $booking
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Booking $booking, EntityManagerInterface $manager)
    {
        $bookingId = $booking->getId();
        $manager->remove($booking);
        $manager->flush();

        $this->addFlash('success', "La réservation <strong>n°{$bookingId} a bien été supprimée</strong>");

       return $this->redirectToRoute('admin_booking_index');
    }
}
