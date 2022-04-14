<?php

namespace App\services;

use App\Entity\Event;
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
     * @brief : Envoie un mail à tous les inscrit de l'évènement
     * @param Event $event
     * @return void
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function envoie(Event $event)
    {
        foreach ($event->getUsers() as $u)
        {
            $email = (new Email())
                ->from('admin@eni.fr')
                ->to($u->getEmail())
                ->subject("Evènement : " . $event->getName())
                ->text("Vous êtes inscrit pour l'évènement : " . $event->getName());

            $this->mailer->send($email);
        }
    }

    /**
     * @brief : Envoie un mail à l'utilisateur, pour valider son inscription
     * @param Event $event
     * @return void
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function confirmInscription(User $user)
    {
        $email = (new Email())
            ->from('admin@eni.fr')
            ->to($user->getEmail())
            ->subject("Bienvenue sur la plateforme Sortie.com" )
            ->text("Félicitation " . $user->getName() . ", tu peux, dès maintenant consulter les évènements à venir.");

        $this->mailer->send($email);
    }
}