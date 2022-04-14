<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\UpdateEventType;
use App\Repository\CampusRepository;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/event', name: 'event')]
class EventController extends AbstractController
{
    #[Route('/', name: '_index')]
    public function index(): Response
    {
        return $this->render('event/index.html.twig', ['controller_name' => 'EventController',]);
    }      
      
    #[Route('/create', name: '_create')]
    public function create(

    ): Response
    {
       
    return $this->renderForm('event/index.html.twig');
    }

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

    #[Route('/update/{id}', name: '_update',requirements: ["id" => "\d+"])]

    public function update(
        Event $event,
        EventRepository $eventRepository,
        EntityManagerInterface $entityManager,
        Request $request,

    ): Response
    {
        $eventForm = $this->createForm(UpdateEventType::class,$event);

        $eventForm->handleRequest($request);

        if ($eventForm->isSubmitted() && $eventForm->isValid()){
            $entityManager->persist($event);
            $entityManager->flush();
            return $this->redirectToRoute('main_index');
        }

        return $this->renderForm('event/update.html.twig',
            compact('eventForm'));
    }
/*
 * todo / A vérifier avec le nouveau formulaire
 */
//    #[Route('/delete/{id}', name: '_delete',requirements: ["id" => "\d+"])]
//    public function delete(
//        Event $event,
//        EventRepository $eventRepository,
//        EntityManagerInterface $entityManager,
//
//    ): Response
//    {
//        $entityManager->remove($event);
//        $entityManager->flush();
//        return $this->redirectToRoute('main_index');
//    }

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
        $usr>removeEvent($ev);

        $em->persist($usr);
        $em->flush();

        return $this->redirectToRoute('main_index');
    }
}

