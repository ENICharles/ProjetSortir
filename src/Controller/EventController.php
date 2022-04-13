<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\UpdateEventType;
use App\Repository\EventRepository;
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
}
}

