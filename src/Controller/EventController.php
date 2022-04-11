<?php

namespace App\Controller;

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
}
