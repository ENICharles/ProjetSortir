<?php

namespace App\services;

use App\Entity\Event;
use App\Entity\User;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class Mailing
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Envoie un mail à l'utilisateur, pour valider son inscription sur la plateforme
     * @param Event $event
     * @return void
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function confirmationInscriptionPlateforme(User $user)
    {
        $email = (new Email())
            ->from('admin@eni.fr')
            ->to($user->getEmail())
            ->subject("Validation d'inscription ")
            ->text("Vous êtes inscrit sur la plateforme, vous pouvez vous connecter avec ce login : ". $user->getEmail() .  " " . $user->getPassword());

        $this->mailer->send($email);
    }

    /**
     * Envoie un mail à l'utilisateur, pour valider son inscription
     * @param Event $event
     * @return void
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function confrimationInscription(Event $event,User $user)
    {
        $email = (new Email())
            ->from('admin@eni.fr')
            ->to($user->getEmail())
            ->subject("Evènement : " . $event->getName())
            ->text("Vous êtes inscrit à l'évènement : " . $event->getName());

        $this->mailer->send($email);
    }

    /**
     * Envoie un mail à l'uttilisateur pour son désistement
     * @param Event $event
     * @return void
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function confrimationDesistement(Event $event,User $user)
    {
        $email = (new Email())
            ->from('admin@eni.fr')
            ->to($user->getEmail())
            ->subject("Evènement : " . $event->getName())
            ->text("Vous n'êtes plus inscrit à l'évènement : " . $event->getName());

        $this->mailer->send($email);
    }

    /**
     * Envoie un mail à tous les uttilisateurs
     * @param Event $event
     * @param string $subject
     * @param string $body
     * @return void
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function sendToAllUserEvent(Event $event, string $subject,string $body)
    {
        foreach ($event->getUsers() as $u)
        {
            $email = (new Email())
                ->from('admin@eni.fr')
                ->to($u->getEmail())
                ->subject($subject)
                ->text($body);

            $this->mailer->send($email);
        }
    }
}