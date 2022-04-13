<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Event;
use App\Entity\State;
use App\Form\EventType;
use App\Repository\CityRepository;
use App\Repository\EventRepository;
use App\Repository\LocalisationRepository;
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
        EntityManagerInterface  $em,
        Request $request,
        UserRepository $ur,
        LocalisationRepository $lr,
        CityRepository $cr,
    ): Response
    {
        $event = new Event();
        $state = new State();

        $eventForm = $this->createForm(EventType::class, $event);

        $eventForm->handleRequest($request);

        if (
            $eventForm->isSubmitted()
            && $eventForm->isValid()
        ) {

            $usr = $ur->findBy(['email' => $this->getUser()->getUserIdentifier()]);
            $event->setOrganisator($usr[0]);

            $local = $lr->findBy(['id' => 1]);
            $event->setLocalisation(($local[0]));

            $state->setLabel('En cours');
            $event->setState($state);

            $em->persist($event);
            $em->flush();
            $this->addFlash(
                'success',
                'La sortie a bien été créée !'
            );
        }
    return $this->renderForm('event/index.html.twig',
        compact("eventForm",)
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