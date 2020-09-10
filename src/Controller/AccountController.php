<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\Newsletter;
use App\Entity\PasswordUpdate;
use App\Entity\PromoCode;
use App\Entity\User;
use App\Form\AccountType;
use App\Form\ForgetPasswordType;
use App\Form\PasswordUpdateType;
use App\Form\RegistrationType;
use App\Form\ResetPasswordType;
use App\Repository\BookingRepository;
use App\Repository\LikeRepository;
use App\Repository\NewsletterRepository;
use App\Repository\PromoCodeRepository;
use App\Repository\UserRepository;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends AbstractController
{
    /**
     * Permet d'afficher et de gerer le formulaire de deconnexion
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
            $registration = $request->request->all()['registration'];

            $hash = $encoder->encodePassword($user, $user->getHash());
            try {
                $user->setHash($hash)
                    ->setUpdatedAt(new \DateTime('now'));
                $manager->persist($user);
                $manager->flush();
            } catch(\Exception $e){
                $this->addFlash('danger', 'Erreur: ' . $e->getCode() . '. Veuillez réessayer');
                return $this->redirectToRoute('account_register');
                }


            if ($registration['newsletter'] !== null && !$newsletterRepository->findOneBy(['email'=> $registration['email']])){
                $newsletterRegister = new Newsletter();
                $newsletterRegister->setEmail($registration['email'])
                    ->setFirstname($registration['firstName'])
                    ->setLastname($registration['lastName']);

                $manager->persist($newsletterRegister);
                $this->addFlash('success', "Votre compte a bien été créé et votre inscription à la newsletter prise en compte !<br>Vous pouvez vous connecter !"  );
            }else{
                $this->addFlash('success', "Votre compte a bien été créé !<br>Vous pouvez vous connecter !");
            }

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
     * @param Request $request
     * @return Response
     * @Route("/forget-password", name="account_forget_password")
     */
    public function forgetPassword(Request $request, UserRepository $userRepository, TokenGeneratorInterface $tokenGenerator, EntityManagerInterface $manager, MailerService $mailerService)
    {

        $form = $this->createForm(ForgetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $donnees = $form->getData();

            $user = $userRepository->findOneBy(['email' => $donnees['email']]);

            if (!$user) {
                $this->addFlash('danger', 'Cette adresse e-mail est inconnue');

                return $this->redirectToRoute('account_login');
            }

            $token = $tokenGenerator->generateToken();

            try{
                $user->setResetToken($token);
                $manager->persist($user);
                $manager->flush();
            } catch (\Exception $e) {
                $this->addFlash('warning', $e->getMessage());
                return $this->redirectToRoute('account_login');
            }

            $url = $this->generateUrl('account_reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

            $mailerService->resetPassword($user, $url);

            $this->addFlash('success', 'Un email de réinitialisation du mot de passe vous a été envoyé');

            return $this->redirectToRoute('account_login');

        }


            return $this->render('account/forgetPassword.html.twig',
            [
                'form' => $form->createView()
            ]);
    }


    /**
     * @Route("/reset-password/{token}", name="account_reset_password")
     */
    public function resetPassword(UserRepository $userRepository, $token, Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager)
    {
        $user = $userRepository->findOneBy(['reset_token' => $token]);
        if (!$user) {
            $this->addFlash('danger', 'Accès refusé veuillez réessayer ou renouveler la demande');
            return $this->redirectToRoute('account_login');
        }

        $tenMinutes = 10*60*1000;
        $now = new \DateTime('now');
        if (($user->getTokenUpdatedAt()->getTimestamp() + $tenMinutes) <  $now->getTimestamp()) {
            $this->addFlash('danger', 'La demande de réinitialisation a expiré');
            return $this->redirectToRoute('account_login');
        }

        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            if ($data['password'] === $data['confirm_password']){
                $hash = $encoder->encodePassword($user, $data['password']);
                $user->setHash($hash)
                     ->setUpdatedAt(new \DateTime('now'))
                     ->setResetToken(null);


                $manager->persist($user);
                $manager->flush();

                $this->addFlash('success', 'Votre mot de passe a bien été modifié');

                return $this->redirectToRoute('account_login');

            }

        }

        return $this->render('account/resetPassword.html.twig',
            [
                'form' => $form->createView(),
                'token' => $token
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
     * @IsGranted("ROLE_USER")
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


    /**
     * @param Booking $booking
     * @param MailerInterface $mailer
     * @param PromoCodeRepository $promoCodeRepository
     * @param EntityManagerInterface $manager
     * @param MailerService $mailerService
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("account/booking-cancel/{id}", name="account_booking_cancel")
     */
    public function bookingCancel(Booking $booking, MailerInterface $mailer, PromoCodeRepository $promoCodeRepository, EntityManagerInterface $manager, MailerService $mailerService, Request $request)
    {
        $relaunchChecker = $promoCodeRepository->findOneBy(['user' => $this->getUser()]);
        $bookingId = $booking->getId();
        if (!$relaunchChecker && $this->getUser()){
            $promoCode = new PromoCode();
            $promoCode
                ->setCode('10R' . uniqid())
                ->setMaxNumber('1')
                ->setUser($this->getUser())
                ->setAmount('10')
                ->setType('POURCENTAGE');

            $manager->persist($promoCode);

            $mailerService->relance($this->getUser(), $promoCode);

        }
        if ($this->isCsrfTokenValid('delete' . $booking->getId(), $request->get('_token'))){
            $manager->remove($booking);
            $manager->flush();

            $this->addFlash('success', "La réservation <strong>n°{$bookingId} a bien été supprimée</strong>");
        }

        return $this->redirectToRoute('account_bookings');

    }


}
