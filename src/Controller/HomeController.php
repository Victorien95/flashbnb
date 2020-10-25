<?php

namespace App\Controller;

use App\Repository\AdRepository;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(AdRepository $adRepository, UserRepository $userRepository, SessionInterface $session)
    {
        return $this->render('home/index.html.twig', [
            'bestAds' => $adRepository->findBestAds(3),
            'bestUsers' => $userRepository->findBestUsers(2)
        ]);
    }
}
