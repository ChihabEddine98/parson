<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/users", name="user_list")
     */
    public function index()
    {
        return $this->render('user/user_list.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/users/me", name="user_profile")
     */
    public function profile()
    {
        return $this->render('user/user_profile.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}
