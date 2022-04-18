<?php

namespace App\Controller;

use App\Entity\City;
use App\Form\CityType;
use App\Repository\CityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/city', name: 'city')]
class CityController extends AbstractController
{

    #[Route('/create/city', name: '_create')]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager,
        CityRepository $sr): Response
    {

        $city = new City();

        $citytForm = $this->createForm(CityType::class,$city);
        $citytForm->handleRequest($request);

        if($citytForm->isSubmitted() && $citytForm->isValid())
        {
            $entityManager->persist($city);
            $entityManager->flush();

            return $this->redirectToRoute('main_index');
        }
        else
        {
            return $this->renderForm('city/create.html.twig',compact('citytForm'));
        }
    }
}
