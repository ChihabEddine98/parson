<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/users", name="user_list")
     */
    public function index(UserRepository $repo)
    {
        $users=$repo->findAll();

        return $this->render('user/user_list.html.twig', [
            'controller_name' => 'UserController',
             'users'=> $users
        ]);
    }

    /**
     * @Route("/users/{id}", name="user_profile")
     */
    public function profile(User $user)
    {
        return $this->render('user/user_profile.html.twig', [
            'controller_name' => 'UserController',
            'user'=>$user
        ]);
    }
}
