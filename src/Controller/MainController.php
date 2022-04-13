<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Event;
use App\Repository\CampusRepository;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Boolean;
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
    public function index(CampusRepository $cr, EventRepository $er): Response
    {
        if($this->getUser())
        {
            $lstCampus = $cr->findAll();
            $lstEvent  = $er->findAll();

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
        $lstCampus = $cr->findAll();

        $usr = $ur->findBy(['email'  => $this->getUser()->getUserIdentifier()]);

        /* supprime l'évènement de l'utilisateur */
        $usr[0]->addPopEvent($ev);

        $em->persist($usr[0]);
        $em->flush();

        return $this->redirectToRoute('main_index');
    }

    #[Route('/search', name: '_search')]
    public function search(EntityManagerInterface $em,UserRepository $ur, CampusRepository $cr, EventRepository $er, Request  $request): Response
    {
        $lstCampus = $cr->findAll();
        $allEvent  = $er->findAll();

        $usr = $ur->findOneBy(['email'  => $this->getUser()->getUserIdentifier()]);

        $lstEvent = array();

        $filtre = $request->query->get('search');
        $newArray = array_filter($allEvent,function (Event $element) use ($filtre)
        {
            if($element->getName() === $filtre)
            {
                $ret = true;
            }
            else
            {
                $ret = false;
            }

            return $ret;
        }, ARRAY_FILTER_USE_BOTH);



        $filtre = $request->query->get('campus');
        $newArray = array_filter($newArray,function (Event $element) use ($filtre)
        {
            if($element->getCampus() === $filtre)
            {
                $ret = true;
            }
            else
            {
                $ret = false;
            }

            return $ret;
        }, ARRAY_FILTER_USE_BOTH);

        dd($newArray);

        return $this->render('main/index.html.twig', compact('lstCampus','lstEvent'));
    }
}


