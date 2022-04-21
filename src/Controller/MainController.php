<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Filter;

use App\Form\FilterType;
use App\Repository\CampusRepository;
use App\Repository\EventRepository;
use App\Repository\FilterRepository;
use App\Repository\StateRepository;
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
            return $this->redirectToRoute('main_search');
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
    public function search(
        EntityManagerInterface $em,
        UserRepository $ur,
        CampusRepository $cr,
        EventRepository $er,
        FilterRepository $filterRepository,
        Request  $request,
        StateRepository $st
    ): Response
    {
        /* recherche tous les campus */
        $listCampus     = $cr->findAll();

        /* recherche tous les campus */
        $listState    = $st->findAll();

        /* récupération de l'utilisateur */
        $usr            = $ur->findOneBy(['email'  => $this->getUser()->getUserIdentifier()]);

        /* récupération du campus de l'utilisateur */
        $selectedCampus = $usr->getCampus();

        /* récupération de tous les évènements d'un campus */
        $listEvent = $er->findAll1M($selectedCampus);

        $filter = new Filter();
        $filterForm = $this->createForm(FilterType::class, $filter);

        $filterForm->handleRequest($request);

        if ($filterForm->isSubmitted() && $filterForm->isValid())
        {
            if(($filter->getDateStart() == null))
            {
                $filter->setDateStart(new DateTime());
            }

            if(($filter->getDateEnd() == null))
            {
                $filter->setDateEnd(new DateTime());
            }
            if(($filter->getName() == null))
            {
                $filter->setName(' ');
            }

            /* récupération du campus de l'utilisateur */
            $selectedCampus = $filter->getCampus();

            $listEvent = $er->findbyFilter( $selectedCampus,
                                            $filter->getDateStart(),
                                            $filter->getDateEnd(),
                                            $filter->getName(),
                                            $filter->getIsOrganisator(),
                                            $filter->getIsRegistered(),
                                            $filter->getIsNotRegistered(),
                                            $filter->getIsPassedEvent(),
                                            $usr);

            return $this->renderForm('main/index.html.twig',compact( 'filterForm', 'listEvent'));
        }

        return $this->renderForm('main/index.html.twig',compact( 'filterForm', 'listEvent','listState'));
    }
}


