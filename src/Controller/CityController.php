<?php

//namespace App\Controller;
//
//use App\Entity\City;
//use App\Entity\User;
//use App\Form\CityType;
//use App\Repository\CityRepository;
//use App\Repository\UserRepository;
//use Doctrine\ORM\EntityManagerInterface;
//use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\Routing\Annotation\Route;
//
///**
// * Ajout d'une ville avec son code postal(seulement par admin)
// */
//#[Route('/city', name: 'city')]
//class CityController extends AbstractController
//{
//    #[Route('/create/city', name: '_create')]
//    public function create(
//        Request $request,
//        EntityManagerInterface $entityManager,
//        CityRepository $sr,
//        UserRepository $ur,
//        ): Response
//    {
//        $usr = $ur->findOneBy(['email'  => $this->getUser()->getUserIdentifier()]);
//
//        if($usr->getIsAdmin())
//        {
//            $city = new City();
//
//            $citytForm = $this->createForm(CityType::class, $city);
//            $citytForm->handleRequest($request);
//
//            if ($citytForm->isSubmitted() && $citytForm->isValid())
//            {
//                $entityManager->persist($city);
//                $entityManager->flush();
//
//                return $this->redirectToRoute('main_index');
//            }
//            else
//            {
//                return $this->renderForm('city/create.html.twig', compact('citytForm'));
//            }
//        }
//        else
//        {
//            return $this->redirectToRoute('main_index');
//        }
//    }
//
//    /**
//     * Modification d'une ville(seulement par admin)
//     * @param Request $request
//     * @param EntityManagerInterface $entityManager
//     * @param CityRepository $sr
//     * @param UserRepository $ur
//     * @param City $city
//     * @param User $user
//     * @return Response
//     */
//    #[Route('/update/city/{id}', name: '_update')]
//    public function update(
//        Request $request,
//        EntityManagerInterface $entityManager,
//        CityRepository $sr,
//        UserRepository $ur,
//        City $city,
//        User $user): Response
//    {
//        $usr = $ur->findOneBy(['email'  => $this->getUser()->getUserIdentifier()]);
//
//        if($usr->getIsAdmin())
//        {
//            $citytForm = $this->createForm(CityType::class, $city);
//            $citytForm->handleRequest($request);
//
//            if ($citytForm->isSubmitted() && $citytForm->isValid()) {
//                $entityManager->persist($city);
//                $entityManager->flush();
//
//                return $this->redirectToRoute('main_index');
//            } else {
//                return $this->renderForm('city/create.html.twig', compact('citytForm'));
//            }
//        }
//        else
//        {
//            return $this->redirectToRoute('main_index');
//        }
//    }
//
//    /**
//     * * Suppression d'une ville(seulement par admin)
//     * @param Request $request
//     * @param EntityManagerInterface $entityManager
//     * @param CityRepository $sr
//     * @param UserRepository $ur
//     * @param City $city
//     * @param User $user
//     * @return Response
//     */
//    #[Route('/delete/city/{id}', name: '_delete')]
//    public function delete(
//        Request $request,
//        EntityManagerInterface $entityManager,
//        CityRepository $sr,
//        UserRepository $ur,
//        City $city,
//        User $user
//        ): Response
//    {
//        $usr = $ur->findOneBy(['email'  => $this->getUser()->getUserIdentifier()]);
//
//        /* ctrl si l'utilisateur connecté est l'organisateur de l'evenement */
//        if($usr->getIsAdmin())
//        {
//            $entityManager->remove($city);
//            $entityManager->flush();
//        }
//
//        return $this->redirectToRoute('main_index');
//    }
//}
