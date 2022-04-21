<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Localisation;
use App\Form\LocalisationType;
use App\Repository\LocalisationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/localisation', name: 'localisation')]
class LocalisationController extends AbstractController
{
    #[Route('/new', name: '_new')]
    public function new(
        Request                $request,
        EntityManagerInterface $entityManager,
        LocalisationRepository $localisationRepository
    ): Response
    {
        $found = false;
        $local = new Localisation();
        $city = new City();
        $local->setCity($city);
        $localisationForm = $this->createForm(LocalisationType::class, $local);
        $localisationForm->handleRequest($request);

        if ($localisationForm->isSubmitted() && $localisationForm->isValid()) {
            $street = $localisationForm['street']->getData();
            $name = $localisationForm['name']->getData();
            $cityForm = $localisationForm['city']->getData();
            $localisation = $localisationRepository->findAll();
            foreach ($localisation as $key => $value) {
                if ($value->getStreet() === $street && $value->getName() === $name && $value->getCity() === $city) {
                    //dd($message = "Ce lieu existe déjà");
                    $found = true;
                    $this->addFlash('erreur', 'Lieu déjà existant, création impossible');

                }
            }
            if ($found == false) {
                $local->setName($name);
                $local->setStreet($street);
                $local->setCity($cityForm);
                $entityManager->persist($city);
                $entityManager->persist($local);
                $entityManager->flush();
                return $this->redirectToRoute('event_create');
            }

        }
        return $this->renderForm('localisation/new.html.twig',
            compact('localisationForm'));


    }

    #[Route('/getInfo/{id}', name: '_getInfo')]
    public function getInfo(SerializerInterface $serializer,Localisation $local)
    {
        return new Response($serializer->serialize(
            $local,
            'json', ['groups' => ['lieu']]
        ));

    }
}