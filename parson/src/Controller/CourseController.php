<?php

namespace App\Controller;

use App\Entity\Course;
use App\Form\CourseType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CourseController extends AbstractController
{
    /**
     * @Route("/courses", name="courses")
     */
    public function index()
    {
        $repo=$this->getDoctrine()->getRepository(Course::class);
        $courses=$repo->findAll();

        return $this->render('course/course_list.html.twig', [
            'controller_name' => 'CourseController',
            'courses'=>$courses
        ]);
    }


    /**
     * @Route("/courses/new",name="new_course")
     */
    public function new()
    {
        $form=$this->createForm(CourseType::class);

        return $this->render('course/course_new.html.twig',[
            'courseForm'=>$form->createView()
        ]);
    }


    /**
     * @Route("/courses/me",name="my_courses")
     */

    public function mesCours()
    {
        return $this->render('course/course_list.html.twig', [
            'controller_name' => 'CourseController',
        ]);
    }

    /**
     * @Route("/notes/me",name="my_marks")
     */

    public function mesNotes()
    {
        return $this->render('course/course_notes.html.twig', [
            'controller_name' => 'CourseController',
        ]);
    }


}
