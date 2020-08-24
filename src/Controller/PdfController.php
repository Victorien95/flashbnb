<?php

namespace App\Controller;

use App\Entity\Booking;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class PdfController extends AbstractController
{
    /**
     * @Route("/admin/option/pdf", name="test")
     */
    public function index()
    {
        $snappy = new Pdf("\"C:\\Program Files\\wkhtmltopdf\\bin\\wkhtmltopdf.exe\"");
        $snappy->setTemporaryFolder("C:\mytemp");
        $fileName = 'test.pdf';

        $url = $this->generateUrl('admin_option_index', [
        ]);

        return new PdfResponse(
            $snappy->getOutput('http://127.0.0.1:8000' . $url),
            "$fileName",
        '',
        'attachment');
    }

    /**
     * @Route("ads/pdf/booking/{id}", name="bill_pdf")
     */
    public function pdf(Booking $booking, Pdf $snappy)
    {
        $user = $this->getUser();
        //$snappy = new Pdf('C:\wamp64\www\symbnb\vendor\wemersonjanuario\wkhtmltopdf-windows\bin\64bit\wkhtmltopdf.exe');
        //$snappy->setBinary('C:\wamp64\www\symbnb\vendor\wemersonjanuario\wkhtmltopdf-windows\bin\64bit\wkhtmltopdf.exe');
        //$snappy->setTemporaryFolder('E:\Utilisateurs\Bureau\mytemp');

        /*$html = $this->generateUrl('ads_show',
            [
                'slug' => $ad->getSlug()
            ]);
        dump($html);
        die();*/
        $html = $this->renderView('common/pdf/bill.html.twig',
            [
                'booking' => $booking,
                'user' => $user

            ]);
        $filename = 'Facture_FlashBnb';

        //return new PdfResponse($snappy->getOutputFromHtml($html), 'file.pdf');
        /*return new PdfResponse(
            $snappy->getOutput($pageUrl),
            'file.pdf'
        );*/

        return new Response(
            $snappy->getOutputFromHtml($html),200,array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'attachment; filename="'.$filename.'.pdf"'
            )
        );

    }

}
