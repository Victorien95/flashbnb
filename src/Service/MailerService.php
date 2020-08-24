<?php


namespace App\Service;


use App\Entity\Booking;
use App\Entity\User;
use App\Repository\NewsletterRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerService
{
    private $mailer;
    private $flashBnBmail;

    public function __construct(MailerInterface $mailer)
    {
        $this->flashBnBmail = 'flashbnb@gmail.com';
        $this->mailer = $mailer;
    }

    public function inscription(User $user)
    {
        $email = (new TemplatedEmail())
            ->from($this->flashBnBmail)
            ->to($user->getEmail())
            ->subject('Merci pour votre inscription')

            // path of the Twig template to render
            ->htmlTemplate('mailer/inscription.html.twig')

            // pass variables (name => value) to the template
            ->context([
                'user' => $user
            ])
        ;

        $this->mailer->send($email);
    }

    public function relance(User $user, $promoCode)
    {
        $email = (new TemplatedEmail())
            ->from($this->flashBnBmail)
            ->to($user->getEmail())
            ->subject('Promotion pour vous')

            // path of the Twig template to render
            ->htmlTemplate('mailer/relance.html.twig')

            // pass variables (name => value) to the template
            ->context([
                'promocode' => $promoCode,
                'user' => $user
            ])
        ;

        $this->mailer->send($email);
    }

    public function booking(User $user, Booking $booking)
    {
        $email = (new TemplatedEmail())
            ->from($this->flashBnBmail)
            ->to($user->getEmail())
            ->subject('Promotion pour vous')

            // path of the Twig template to render
            ->htmlTemplate('mailer/booking.html.twig')

            // pass variables (name => value) to the template
            ->context([
                'booking' => $booking,
                'user' => $user
            ])
        ;

        $this->mailer->send($email);
    }

    public function contact(User $user, $subject, $text)
    {
        $email = (new Email())
            ->from($user->getEmail())
            ->to($this->flashBnBmail)
            ->subject('Contact: ' . $user->getLastName() . ' ' . $subject)

            // path of the Twig template to render
            ->html($text)

            // pass variables (name => value) to the template
        ;

        $this->mailer->send($email);
    }

    public function newsletter($subject, $text, NewsletterRepository $repository)
    {
        $mailing = $repository->findAll();

        $email = (new Email())
            ->from($this->flashBnBmail)
            ->to($this->flashBnBmail)
            ->subject($subject)
            ->html($text)
        ;
        foreach ($mailing as $mail){
            $email->addBcc($mail->getEmail());
        }
        $this->mailer->send($email);
    }
}