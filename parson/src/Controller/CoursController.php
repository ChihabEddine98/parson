<?php

namespace App\Controller;

use App\Form\CourseType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CoursController extends AbstractController
{
    /**
     * @Route("/cours", name="course")
     */
    public function index()
    {

        dump("Chihab yooo");
        return $this->render('cours/index.html.twig', [
            'controller_name' => 'CoursController',
        ]);
    }


    /**
     * @Route("cours/new",name="new_course")
     */
    public function new()
    {
        $form=$this->createForm(CourseType::class);

        return $this->render('cours/new.html.twig',[
            'coursForm'=>$form->createView()
        ]);
    }



}
