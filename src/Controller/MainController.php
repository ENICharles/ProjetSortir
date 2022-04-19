<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Filter;
use App\Form\FilterType;
use App\Repository\CampusRepository;
use App\Repository\EventRepository;
use App\Repository\FilterRepository;
use App\Repository\UserRepository;
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
    public function index(CampusRepository $cr, EventRepository $er): Response
    {
        if($this->getUser())
        {
            $listCampus = $cr->findAll();
            $listEvent  = $er->findAll();

//            return $this->render('main/index.html.twig', compact('listCampus','listEvent'));
            return $this->redirectToRoute('main_search');
        }
        else
        {
            return $this->redirectToRoute('app_login');
        }
    }

    #[Route('/search', name: '_search')]
    public function search(
        EntityManagerInterface $em,
        UserRepository $ur,
        CampusRepository $cr,
        EventRepository $er,
        FilterRepository $filterRepository,
        Request  $request): Response
    {
        $filter = new Filter();
//        $filterRepository->findOneBy(['campus' => $this->getUser()->getUserIdentifier()]);
//        $user = $userRepository->findOneBy(['campus' => $this->getUser()->getUserIdentifier()]);
        $filterForm = $this->createForm(FilterType::class, $filter);
        $filterForm->handleRequest($request);

        if ($filterForm->isSubmitted() && $filterForm->isValid()){

            return $this->redirectToRoute('main_index');
        }
       // return $this->redirectToRoute('main_search');

//        $listCampus = $cr->findAll();
//
//        $qb = $er->createQueryBuilder('e');
//
//        if($request->query->get('eventOld'))
//        {
//            $dateNowM1M    = (new \DateTime())->modify('-1 month');
//
//            $qb
//                ->andWhere('e.dateStart > :from')
//                ->setParameter('from', $dateNowM1M );
//        }
//        else
//        {
//            $dateDebut  = new \DateTime($request->query->get('dateStart'));
//            $dateFin    = new \DateTime($request->query->get('dateEnd'));
//
//            $qb
//                ->andWhere('e.dateStart BETWEEN :from AND :to')
//                ->setParameter('from', $dateDebut )
//                ->setParameter('to', $dateFin);
//        }
//
//        $listEvent = $qb->getQuery()->getResult();
//
//        $usr = $ur->findOneBy(['email'  => $this->getUser()->getUserIdentifier()]);
//
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

        $listEvent = $er->findAll();

        return $this->renderForm('main/index.html.twig',
            compact( 'filterForm', 'listEvent'));
//            compact('listCampus','listEvent', 'filterForm'));
    }
}


