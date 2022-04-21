<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\CampusRepository;
use App\Repository\EventRepository;
use App\Repository\StateRepository;
use App\Repository\UserRepository;
use App\services\Mailing;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/event', name: 'event')]
class EventController extends AbstractController
{
    /**
     * Fonction qui crée une sortie
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param UserRepository $userRepository
     * @param StateRepository $st
     * @return Response
     */
    #[Route('/create', name: '_create')]
    public function create(
    Request $request,
    EntityManagerInterface $entityManager,
    UserRepository $userRepository,
    StateRepository $st

    ): Response
    {
        $etat= $st->findOneBy(['id'=> 1]);

        $user = $userRepository->findOneBy(['email'  => $this->getUser()->getUserIdentifier()]);

        $event = new Event();
        $event->setOrganisator($user);
        $event->setState($etat);

        $eventForm = $this->createForm(EventType::class,$event);

        $eventForm->handleRequest($request);

        if ($eventForm->isSubmitted() && $eventForm->isValid())
        {
            $dateStart = $eventForm['dateStart']->getData();
            $dateLimit = $eventForm['inscriptionDateLimit']->getData();
            if($dateLimit < $dateStart){
                $entityManager->persist($event);
                $entityManager->flush();
                return $this->redirectToRoute('main_index');
            }else{
                $this->addFlash('date_error','La date de limite d\'inscription ne peut pas être supérieur à la date de début de la sortie');
                return $this->redirectToRoute('event_create');
            }
        }
        return $this->renderForm('event/update.html.twig',compact('eventForm'));
    }

    /**
     * Fonction qui affiche le détail complet de la sortie
     * @param EventRepository $eventRepository
     * @param Event $event
     * @return Response
     */
    #[Route('/detail/{id}', name: '_detail',requirements: ["id" => "\d+"])]
    public function detail(
        EventRepository $eventRepository,
        Event $event
    ): Response
    {
        return $this->render('event/detail.html.twig',
            compact('event')
        );
    }

    /**
     * Fonction qui permet de modifier une sortie
     * @param Event $event
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param UserRepository $ur
     * @return Response
     */
    #[Route('/update/{id}', name: '_update',requirements: ["id" => "\d+"])]
    public function update(
        Event $event,
        EntityManagerInterface $entityManager,
        Request $request,
        UserRepository $ur
    ): Response
    {
        $eventForm = $this->createForm(EventType::class,$event);

        $usr = $ur->findOneBy(['email'  => $this->getUser()->getUserIdentifier()]);

        /* ctrl si l'utilisateur connecté est l'organisateur de l'evenement */
        if($usr->getId() == $event->getOrganisator()->getId())
        {
            $eventForm->handleRequest($request);

            if ($eventForm->isSubmitted() && $eventForm->isValid())
            {
                $entityManager->persist($event);
                $entityManager->flush();
                return $this->redirectToRoute('main_index');
            }
        }
        return $this->renderForm('event/update.html.twig',
            compact('eventForm'));
    }

    /**
     * Fonction qui supprime une sortie sur l'affichage et en base de données
     * @param Event $event
     * @param EntityManagerInterface $entityManager
     * @param UserRepository $ur
     * @return Response
     */
    #[Route('/delete/{id}', name: '_delete',requirements: ["id" => "\d+"])]
    public function delete(
        Event $event,
        EntityManagerInterface $entityManager,
        UserRepository $ur
    ): Response
    {
        $usr = $ur->findOneBy(['email'  => $this->getUser()->getUserIdentifier()]);

        /* ctrl si l'utilisateur connecté est l'orgnanisateur de l'evenement */
        if($usr->getId() == $event->getOrganisator()->getId())
        {
            $entityManager->remove($event);
            $entityManager->flush();
        }
        return $this->redirectToRoute('main_index');
    }

    /**
     * Inscription à une sortie
     * @param EntityManagerInterface $em
     * @param UserRepository $ur
     * @param Event $ev
     * @return Response
     */
    #[Route('/inscription/{id}', name: '_inscription')]

    public function inscription(
        EntityManagerInterface $em,
        UserRepository $ur,
        Mailing $mailing,
        Event $ev
    ): Response
    {
       $usr = $ur->findOneBy(['email'  => $this->getUser()->getUserIdentifier()]);

        /* Ajout l'évènement de l'utilisateur */
        if($ev->getInscriptionDateLimit() >= new \DateTime('now'))
        {
            $usr->addEvent($ev);
            $mailing->confrimationInscription($ev);
            $ev->setNbMaxInscription($ev->getNbMaxInscription()-1);
            $em->persist($usr);
            $em->flush();
        }
        return $this->redirectToRoute('main_index');
    }

    /**
     * Se désinscrire d'un sortie
     * @param EntityManagerInterface $em
     * @param UserRepository $ur
     * @param Event $ev
     * @return Response
     */
    #[Route('/desinscription/{id}', name: '_desinscription')]

    public function desinscription(
        EntityManagerInterface $em,
        Mailing $mailing,
        UserRepository $ur,
        Event $ev
    ): Response
    {
       $usr = $ur->findOneBy(['email'  => $this->getUser()->getUserIdentifier()]);

        /* supprime l'évènement de l'utilisateur */
        if($ev->getInscriptionDateLimit() > (new \DateTime()))
        {
            $mailing->confrimationDesistement($ev);
            $usr->removeEvent($ev);
            $ev->setNbMaxInscription($ev->getNbMaxInscription() + 1);
            $em->persist($usr);
            $em->flush();
        }
        return $this->redirectToRoute('main_index');
    }

    /**
     * Fonction d'annulation d'un sortie
     * @param EntityManagerInterface $em
     * @param UserRepository $ur
     * @param StateRepository $sr
     * @param Event $ev
     * @param Mailing $mailing
     * @return Response
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    #[Route('/annulation/{id}', name: '_annulation')]
    public function annulation(
        EntityManagerInterface $em,
        UserRepository $ur,
        StateRepository $sr,
        Event $ev,
        Mailing $mailing
    ): Response
    {
        $usr = $ur->findOneBy(['email'  => $this->getUser()->getUserIdentifier()]);

        /* annule l'évènement */
        if($ev->getInscriptionDateLimit() > (new \DateTime()))
        {
            /* modification de l'évènement */
            $ev->setState($sr->findOneBy(['id'=>6]));
            $ev->setNbMaxInscription(0);
            $mailing->sendToAllUserEvent(
                $ev,
                "Annulation de l'évènement ". $ev->getName(),
                "L'évènement ". $ev->getName() ." est annulé");
            $em->persist($usr);
            $em->flush();
        }
        return $this->redirectToRoute('main_index');
    }
}

