<?php

namespace App\Controller;

use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class TestController extends AbstractController
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
     * @Route("ads/pdf", name="test_pdf")
     */
    public function pdf()
    {
        $snappy = new Pdf('C:\wamp64\www\symbnb\vendor\wemersonjanuario\wkhtmltopdf-windows\bin\64bit\wkhtmltopdf.exe');
        $snappy->setTemporaryFolder('C:\mytemp');
        $html = $this->generateUrl('ads_show');



        return new PdfResponse($snappy->getOutput("http://127.0.0.1:8000/ads/fugit-ut-eum-mollitia-inventore-ut-aut-sapiente"));

    }

}
