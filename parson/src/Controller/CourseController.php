<?php

namespace App\Controller;

use App\Form\CourseType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CourseController extends AbstractController
{
    /**
     * @Route("/course", name="course")
     */
    public function index()
    {

        dump("Chihab yooo");
        return $this->render('course/index.html.twig', [
            'controller_name' => 'CourseController',
        ]);
    }


    /**
     * @Route("course/new",name="new_course")
     */
    public function new()
    {
        $form=$this->createForm(CourseType::class);

        return $this->render('course/new.html.twig',[
            'courseForm'=>$form->createView()
        ]);
    }



}
