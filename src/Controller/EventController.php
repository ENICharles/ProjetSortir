<?php

namespace App\Controller;

use App\Entity\Event;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/event', name: 'event')]
class EventController extends AbstractController
{
    #[Route('/index', name: '_index')]
    public function index(): Response
    {
        return $this->render('event/index.html.twig', ['controller_name' => 'EventController',]);
    }

    #[Route('/detail/{id}', name: '_detail')]
    public function detail(
        EventRepository $eventRepository,
        Event $event
    ): Response
    {
        return $this->render('event/detail.html.twig',
        compact('event'));
    }
}
