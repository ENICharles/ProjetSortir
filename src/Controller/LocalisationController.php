<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/localisation', name: 'localisation')]
class LocalisationController extends AbstractController
{
    #[Route('/index', name: '_index')]
    public function index(): Response
    {
        return $this->render('localisation/index.html.twig', ['controller_name' => 'LocalisationController',]);
    }
}
