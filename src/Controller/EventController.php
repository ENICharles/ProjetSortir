<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Localisation;
use App\Entity\User;
use App\Form\EventType;
use App\Repository\CampusRepository;
use App\Repository\EventRepository;
use App\Repository\StateRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/event', name: 'event')]
class EventController extends AbstractController
{
    /**
     * Fonction qui permet de créer une sortie
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param User $user
     * @param StateRepository $st
     * @return Response
     */
    #[Route('/create/{id}', name: '_create',requirements: ["id" => "\d+"] )]
    public function create(
    Request $request,
    EntityManagerInterface $entityManager,
    User $user,
    StateRepository $st
    ): Response
    {
        $etat= $st->findOneBy(['id'=> 1]);

        $user->getId();

        $event = new Event();
        $event->setOrganisator($user);

        $local = new Localisation();
        $event->setLocalisation($local);
        $local->addEvent($event);
        $event->setState($etat);

        $eventForm = $this->createForm(EventType::class,$event);

        $eventForm->handleRequest($request);

        if ($eventForm->isSubmitted() && $eventForm->isValid()){

            $entityManager->persist($local);
            $entityManager->persist($event);

            $entityManager->flush();
            return $this->redirectToRoute('main_index');
        }
        return $this->renderForm('event/update.html.twig',
            compact('eventForm'));

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
     * @param EventRepository $eventRepository
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    #[Route('/update/{id}', name: '_update',requirements: ["id" => "\d+"])]
    public function update(
        Event $event,
        EventRepository $eventRepository,
        EntityManagerInterface $entityManager,
        Request $request,
    ): Response
    {
        $eventForm = $this->createForm(EventType::class,$event);

        $eventForm->handleRequest($request);

        if ($eventForm->isSubmitted() && $eventForm->isValid()){
            $entityManager->persist($event);
            $entityManager->flush();
            return $this->redirectToRoute('main_index');
        }

        return $this->renderForm('event/update.html.twig',
            compact('eventForm'));
    }

    /**
     * Fonction qui supprime une sortie sur l'affichage et en base de données
     * @param Event $event
     * @param EventRepository $eventRepository
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/delete/{id}', name: '_delete',requirements: ["id" => "\d+"])]
    public function delete(
        Event $event,
        EventRepository $eventRepository,
        EntityManagerInterface $entityManager,

    ): Response
    {
        $entityManager->remove($event);
        $entityManager->flush();
        return $this->redirectToRoute('main_index');
    }

    #[Route('/inscription/{id}', name: '_inscription')]
    public function inscription(EntityManagerInterface $em,UserRepository $ur, CampusRepository $cr, EventRepository $er, Request  $request,Event $ev): Response
    {
        $lstCampus = $cr->findAll();

        $usr = $ur->findBy(['email'  => $this->getUser()->getUserIdentifier()]);

        /* Ajout l'évènement de l'utilisateur */
        $usr[0]->addEvent($ev);

        $em->persist($usr[0]);
        $em->flush();

        return $this->redirectToRoute('main_index');
    }

    #[Route('/desinscription/{id}', name: '_desinscription')]
    public function desinscription(EntityManagerInterface $em,UserRepository $ur, CampusRepository $cr, EventRepository $er, Request  $request,Event $ev): Response
    {
        $lstCampus = $cr->findAll();

        $usr = $ur->findOneBy(['email'  => $this->getUser()->getUserIdentifier()]);

        /* supprime l'évènement de l'utilisateur */
        $usr->removeEvent($ev);

        $em->persist($usr);
        $em->flush();

        return $this->redirectToRoute('main_index');
    }
}

