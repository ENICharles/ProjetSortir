<?php

namespace App\Controller;

use App\Entity\Event;
use App\Repository\CampusRepository;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\services\Mailing;


#[Route('/',name:'main')]
class MainController extends AbstractController
{
    #[Route('/', name: '_index')]
    public function index(CampusRepository $cr,UserRepository $ur,EventRepository $er): Response
    {
        if($this->getUser())
        {
            $listCampus = $cr->findAll();
            $usrCampus = ($ur->findOneBy(['email'  => $this->getUser()->getUserIdentifier()]))->getCampus();//  TODO : passer le campus de l'utilisateur au formulaire

            /* recherche des évènements de moins d'un mois ayant pour campus : $listCampus[0] */
            $listEvent  = $er->findAll1M($listCampus[0]);

            return $this->render('main/index.html.twig', compact('listCampus','listEvent'));
        }
        else
        {
            return $this->redirectToRoute('app_login');
        }
    }

    /**
     * Gestion des filtres
     * @param EntityManagerInterface $em
     * @param UserRepository $ur
     * @param CampusRepository $cr
     * @param EventRepository $er
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    #[Route('/search', name: '_search')]
    public function search(EntityManagerInterface $em,UserRepository $ur, CampusRepository $cr, EventRepository $er, Request  $request): Response
    {
        /* recherche tous les campus */
        $listCampus     = $cr->findAll();
        $selectedCampus = $cr->findOneBy(['name'=>$request->query->get('campus')]);
        $usr            = $ur->findOneBy(['email'  => $this->getUser()->getUserIdentifier()]);

        /* si les dates sont remplies ET que le filtre passé n'est pas selectionné */
        if(($request->query->get('dateStart')) and ($request->query->get('dateEnd')) && (!$request->query->get('eventOld')))
        {
            $dateDebut  = new DateTime($request->query->get('dateStart'));
            $dateFin    = new DateTime($request->query->get('dateEnd'));
            $older      = false;
        }
        else
        {
            /* si les dates ne sont pas remplie ET le filtre passé n'est pas selectionné */
            if((!$request->query->get('dateStart')) and (!$request->query->get('dateEnd')) and (!$request->query->get('eventOld')))
            {
                $dateDebut = (new DateTime())->modify('-1 month');
                $dateFin   = (new DateTime())->modify('+42 year');
                $older      = false;
            }
            else
            {
                /* si pas de date mais filtre passé */
                if((!$request->query->get('dateStart')) and (!$request->query->get('dateEnd')) and ($request->query->get('eventOld')))
                {
                    $dateDebut = (new DateTime())->modify('-1 month');
                    $dateFin   = (new DateTime())->modify('+42 year');
                    $older = true;
                }
                else
                {
                    dump('autre filtre');
                }
            }
        }

        $motClef        = $request->query->get('search');
        $isManager      = null;
        $isInscrit      = null;
        $isNotInscrit   = null;
        $isPassed       = false;

        $request->query->get('eventManage') ? $isManager    = $usr : $isManager     = null;
        $request->query->get('eventIns')    ? $isInscrit    = $usr : $isInscrit     = null;
        $request->query->get('eventUnIns')  ? $isNotInscrit = $usr : $isNotInscrit  = null;


        $listEvent = $er->findbyFilter( $selectedCampus,
                                        $dateDebut,
                                        $dateFin,
                                        $motClef,
                                        $isManager,
                                        $isInscrit,
                                        $isNotInscrit,
                                        $isPassed,
                                        $usr);

        return $this->render('main/index.html.twig', compact('listCampus','listEvent'));
    }
}


