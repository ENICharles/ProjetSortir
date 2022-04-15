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
    public function index(CampusRepository $cr,EventRepository $er): Response
    {
        if($this->getUser())
        {
            $listCampus = $cr->findAll();

            /* recherche des évènements de moins d'u mois */
            $listEvent  = $er->findAll1M($listCampus[0]);

            return $this->render('main/index.html.twig', compact('listCampus','listEvent'));
        }
        else
        {
            return $this->redirectToRoute('app_login');
        }
    }

    #[Route('/search', name: '_search')]
    public function search(EntityManagerInterface $em,UserRepository $ur, CampusRepository $cr, EventRepository $er, Request  $request): Response
    {
        /* recherche tous les campus */
        $listCampus     = $cr->findAll();
        $selectedCampus = $cr->findOneBy(['name'=>$request->query->get('campus')]);
        $usr            = $ur->findOneBy(['email'  => $this->getUser()->getUserIdentifier()]);
        $isInscrit      = null;

//        if($request->query->get('eventOld'))
//        {
//            $listEvent  = $er->findAll1M($selectedCampus);
//        }
//        else
//        {
//            if(($request->query->get('dateStart')) && ($request->query->get('dateEnd')))
//            {
//                $dateDebut  = new DateTime($request->query->get('dateStart'));
//                $dateFin    = new DateTime($request->query->get('dateEnd'));
//
//                $listEvent  = $er->findbyDate($selectedCampus,$dateDebut,$dateFin);
//            }
//            else
//            {
//                $listEvent = $er->findAll1M($selectedCampus);
//            }
//        }

        if(($request->query->get('dateStart')) and ($request->query->get('dateEnd')))
        {
            $dateDebut  = new DateTime($request->query->get('dateStart'));
            $dateFin    = new DateTime($request->query->get('dateEnd'));
        }
        else
        {
            $dateDebut = (new DateTime())->modify('-1 month');
            $dateFin   = (new DateTime())->modify('+42 year');
        }

        if(!$request->query->get('eventIns'))
        {
            $isInscrit = null;
        }
        else
        {
            $isInscrit = $usr;
        }

        $listEvent = $er->findbyFilter($selectedCampus,$dateDebut,$dateFin,$request->query->get('search'),'true',$isInscrit,'true',$isInscrit,$usr);


        return $this->render('main/index.html.twig', compact('listCampus','listEvent'));

//        if($request->query->get('search'))
//        {
//            $filtre = $request->query->get('search');
//            $newArray = array_filter($listEvent, function (Event $element) use ($filtre)
//            {
//                if (str_contains($element->getName(),$filtre))
//                {
//                    $ret = true;
//                } else {
//                    $ret = false;
//                }
//
//                return $ret;
//            }, ARRAY_FILTER_USE_BOTH);
//
//            $listEvent = $newArray;
//        }
//
//        if($request->query->get('campus'))
//        {
//            $filtre = $request->query->get('campus');
//            $newArray = array_filter($listEvent, function (Event $element) use ($filtre)
//            {
//                if ($element->getCampus()->getName() === $filtre) {
//                    $ret = true;
//                } else {
//                    $ret = false;
//                }
//
//                return $ret;
//            }, ARRAY_FILTER_USE_BOTH);
//
//            $listEvent = $newArray;
//        }
//
//        if($request->query->get('eventManage'))
//        {
//            $filtre = $usr->getId();
//            $newArray = array_filter($listEvent, function (Event $element) use ($filtre)
//            {
//                if ($element->getOrganisator()->getId() === $filtre) {
//                    $ret = true;
//                } else {
//                    $ret = false;
//                }
//
//                return $ret;
//            }, ARRAY_FILTER_USE_BOTH);
//
//            $listEvent = $newArray;
//        }
//
//        if(($request->query->get('eventIns')) && ($request->query->get('eventUnIns')))
//        {
//            $filtre = $usr;
//            $newArray = array_filter($listEvent, function (Event $element) use ($filtre) {
//                if (($element->getUsers()->contains($filtre))  or (!$element->getUsers()->contains($filtre)))
//                {
//                    $ret = true;
//                } else {
//                    $ret = false;
//                }
//
//                return $ret;
//            }, ARRAY_FILTER_USE_BOTH);
//
//            $listEvent = $newArray;
//        }
//        else
//        {
//            if ($request->query->get('eventIns')) {
//                $filtre = $usr;
//                $newArray = array_filter($listEvent, function (Event $element) use ($filtre) {
//                    if ($element->getUsers()->contains($filtre)) {
//                        $ret = true;
//                    } else {
//                        $ret = false;
//                    }
//
//                    return $ret;
//                }, ARRAY_FILTER_USE_BOTH);
//
//                $listEvent = $newArray;
//            }
//
//            if ($request->query->get('eventUnIns'))
//            {
//                dump("pas");
//                $filtre = $usr;
//                $newArray = array_filter($listEvent, function (Event $element) use ($filtre)
//                {
//                    if (!$element->getUsers()->contains($filtre))
//                    {
//                        $ret = true;
//                    }
//                    else
//                    {
//                        $ret = false;
//                    }
//
//                    return $ret;
//                }, ARRAY_FILTER_USE_BOTH);
//
//                $listEvent = $newArray;
//            }
//        }
//
//        if($request->query->get('eventOld'))
//        {
//            $filtre = $usr->getId();
//            $newArray = array_filter($listEvent, function (Event $element) use ($filtre)
//            {
//                if ($element->getOrganisator()->getId() === $filtre) {
//                    $ret = true;
//                } else {
//                    $ret = false;
//                }
//
//                return $ret;
//            }, ARRAY_FILTER_USE_BOTH);
//
//            $listEvent = $newArray;
//        }

//        return $this->render('main/index.html.twig', compact('listCampus','listEvent'));
    }
}


