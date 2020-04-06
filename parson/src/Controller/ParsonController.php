<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ParsonController extends AbstractController
{
    /**
     * @Route("/", name="parson_home")
     */
    public function index()
    {

        return $this->render('parson/welcome.html.twig');
    }
}
