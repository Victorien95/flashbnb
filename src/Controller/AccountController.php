<?php

namespace App\Controller;

use App\Entity\Newsletter;
use App\Entity\PasswordUpdate;
use App\Entity\User;
use App\Form\AccountType;
use App\Form\PasswordUpdateType;
use App\Form\RegistrationType;
use App\Repository\BookingRepository;
use App\Repository\LikeRepository;
use App\Repository\NewsletterRepository;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends AbstractController
{
    /**
     * Permet d'afficher et de ger le formulaire de deconnexion
     *
     * @Route("/login", name="account_login")
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $last_username = $utils->getLastUsername();
        return $this->render('account/login.html.twig',
            [
                'error' => $error !== null,
                'last_username' => $last_username
            ]);
    }


    /**
     * @Route("/logout", name="account_logout")
     * @return void
     */
    public function logout()
    {

    }

    /**
     * Permet d'afficher le formulaire d'inscription
     *
     * @Route("/register", name="account_register")
     * @return Response
     */
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder, MailerService $mailerService, NewsletterRepository $newsletterRepository)
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            if ($form->getData()['newsletter'] && !$newsletterRepository->findOneBy(['email'=> $form->getData()['email']])){
                $newsletterRegister = new Newsletter();
                $newsletterRegister->setEmail($form->getData()['email'])
                    ->setFirstname($form->getData()['firstName'])
                    ->setLastname($form->getData()['lastName']);

                $manager->persist($newsletterRegister);
                $this->addFlash('success', 'Vous êtes bien inscrits à la newsletter FlashBnB !');
            }

            $hash = $encoder->encodePassword($user, $user->getHash());
            $user->setHash($hash)
                ->setUpdatedAt(new \DateTime('now'));


            $manager->persist($user);
            $manager->flush();
            
            $this->addFlash('success', 'Votre compte a bien été créé ! Vous pouvez vous connecter !');

            if ($user){
                $mailerService->inscription($user);
            }

            return $this->redirectToRoute('account_login');
        }

        return $this->render('account/registration.html.twig',
            [
                'form' => $form->createView(),
            ]);
    }


    /**
     * Permet d'afficher et de traiter le formulaire de modification de profil
     *
     * @Route("/account/profile", name="account_profil")
     * @IsGranted("ROLE_USER")
     */
    public function profile(Request $request, EntityManagerInterface $manager)
    {
        $user = $this->getUser();
        $form = $this->createForm(AccountType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $user->setUpdatedAt(new \DateTime('now'));
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', 'Les données du profil ont été enregistrée avec succès');
        }
        return $this->render('account/profil.html.twig',
            [
                'form' => $form->createView()
            ]);
    }

    /**
     * Permet de modifier le mot de passe
     *
     * @Route("/account/password-update", name="account_password")
     * @IsGranted("ROLE_USER")
     */
    public function updatePassword(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager)
    {
        $passwordUpdate = new PasswordUpdate();
        $user = $this->getUser();
        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            if(!password_verify($passwordUpdate->getOldPassword(), $user->getPassword())){
                $form->get('oldPassword')->addError(new FormError("Le mot de passe que vous avez tapez n'est pas le mot de passe actuel"));
            }else{
                $newPassword = $passwordUpdate->getNewPassword();
                $hash = $encoder->encodePassword($user, $newPassword);
                $user->setHash($hash)
                    ->setUpdatedAt(new \DateTime('now'));


                $manager->persist($user);
                $manager->flush();

                $this->addFlash('success', 'Votre mot de passe a bien été modifié');

                return $this->redirectToRoute('home');
            }
        }

        return $this->render('account/password.html.twig',
            [
                'form' => $form->createView()
            ]);
    }

    /**
     * Permet d'afficher le profil de l'utilisateur
     * @Route("/account", name="account_index")
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function myAccount(LikeRepository $likeRepository)
    {
        
        $likes = $likeRepository->findBy(['user'=> $this->getUser()]);
        return $this->render('user/index.html.twig',
            [
                'user' => $this->getUser(),
            ]);

    }

    /**
     * Permet d'afficher la liste des réservations de l'utilisateur
     *
     * @Route("/account/bookings", name="account_bookings")
     * @return Response
     */
    public function bookings(BookingRepository $bookingRepository, PaginatorInterface $knp, Request $request)
    {

        $books = $bookingRepository->findBy(['booker' => $this->getUser()]);

        $paginator = $knp->paginate($books, $request->query->getInt('page', 1),
            5);



        return $this->render('account/bookings.html.twig',
            [
                'paginator' => $paginator
            ]);
    }

}
