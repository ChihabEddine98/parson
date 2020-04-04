<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CoursController extends AbstractController
{
    /**
     * @Route("/cours", name="cours")
     */
    public function index()
    {

        dump("Chihab yooo");
        return $this->render('cours/index.html.twig', [
            'controller_name' => 'CoursController',
        ]);
    }
}
