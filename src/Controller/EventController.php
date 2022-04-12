<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
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
        EntityManagerInterface  $em,
        Request $request
    ): Response
    {
        $event = new Event();
        $eventForm = $this->createForm(EventType::class, $event);

        $eventForm->handleRequest($request);

        if ($eventForm->isSubmitted() && $eventForm->isValid()) {
            $em->persist();
            $em->flush();
//            $this->addFlash(
//                'success',
//                'La sortie a bien été créée !'
//            );
        }
    return $this->renderForm('event/index.html.twig',
        compact("eventForm")
    );
    }

    #[Route('/details/{id}', name: '_details')]
    public function details(
        EventRepository $event,
        Event $eventDetails
    ): Response
    {
        return $this->render('event/details.html.twig',
            compact('eventDetails')
        );
    }
}
