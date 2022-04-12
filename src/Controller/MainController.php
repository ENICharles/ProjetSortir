<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Event;
use App\Repository\CampusRepository;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Expr\Array_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;

#[Route('/',name:'main')]
class MainController extends AbstractController
{
    #[Route('/', name: '_index')]
    public function index(): Response
    {
        if($this->getUser())
        {
            $lstCampus = $cr->findAll();
            $lstEvent  = $er->findAll();

            //dd($lstEvent);

            return $this->render('main/index.html.twig', compact('lstCampus','lstEvent'));
        }
        else
        {
            return $this->redirectToRoute('app_login');
        }
    }

    #[Route('/inscription/{id}', name: '_inscription')]
    public function inscription(EntityManagerInterface $em,UserRepository $ur, CampusRepository $cr, EventRepository $er, Request  $request,Event $ev): Response
    {
        dump("inscription");
        $lstCampus = $cr->findAll();

        $this->getUser()->getUserIdentifier();

        $usr = $ur->findBy(['email'  => $this->getUser()->getUserIdentifier()]);

        $usr[0]->addEvent($ev);

        $em->persist($usr);
        $em->flush();

        return $this->redirectToRoute('main_index');
    }

    #[Route('/search', name: '_search')]
    public function search(EntityManagerInterface $em, CampusRepository $cr, EventRepository $er, Request  $request): Response
    {
        $lstCampus = $cr->findAll();
        $allEvent  = $er->findAll();

        $lstEvent = array();

        foreach($allEvent as $event)
        {
            if(
                (str_contains($event->getName(),$request->query->get('search'))) and
                (str_contains($event->getCampus()->getName(),$request->query->get('campus'))) and
                ($event->getDateStart() >= $request->query->get('dateStart'))
//                ($event->getDateStart() <= $request->query->get('dateEnd'))
            )
            {
                array_push($lstEvent,$event);
            }
        }

        return $this->render('main/index.html.twig', compact('lstCampus','lstEvent'));
    }
}
