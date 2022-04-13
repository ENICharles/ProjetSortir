<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfilType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user', name: 'user')]
class UserController extends AbstractController
{
    #[Route('/index', name: '_index')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [ 'controller_name' => 'UserController', ]);
    }

    #[Route('/profil', name: '_profil')]
    public function profil(
        EntityManagerInterface $em,
        UserRepository $userRepository,
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher
    ): Response
    {
       $profil = $userRepository->findBy(
           ['email'=> $this->getUser()->getUserIdentifier()]);
       $userForm = $this->createForm(ProfilType::class, $profil[0]);
       $userForm->handleRequest($request);
          /*TODO / Vérification du changement de pseudo si pas unique page d'erreur*/

       if ($userForm->isSubmitted() && $userForm->isValid()){
           $profil[0]->setPassword(
               $userPasswordHasher->hashPassword(
                   $profil[0],
                   $userForm->get('password')->getData()
               )
           );
            $em->persist($profil[0]);
            $em->flush();
            return $this->redirectToRoute('main_index');
       }
       return $this->renderForm('user/profil.html.twig',
           compact('userForm'));
    }

    #[Route('/detail/{id}', name: '_detail',requirements: ["id" => "\d+"])]
    public function detail(
        User $user,
        UserRepository $userRepository
    ): Response
    {
        return $this->render(
            'user/detail.html.twig',
            compact("user")
        );
    }

}
