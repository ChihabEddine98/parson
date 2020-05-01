<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\Exercise;
use App\Form\CourseType;
use App\Repository\CourseRepository;
use App\Repository\UserCourseRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
     * @IsGranted("ROLE_ENS")
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
     * @IsGranted("ROLE_USER")
     */

    public function mesNotes()
    {
        return $this->render('course/course_notes.html.twig', [
            'controller_name' => 'CourseController',
        ]);
    }

    /**
     * @Route("/courses/{id}",name="course_detail")
     */

    public function courseDetail(Course $cours,CourseRepository $repo,UserCourseRepository $repoScore)
    {
        $coursSimilaires=$repo->findByCategoryAndNotThisId($cours->getCategory(),$cours->getId());
        $moyenne=$repoScore->findAverageByCourse($cours);
        $avisMoyen=$repoScore->findAverageRateByCourse($cours);

        return $this->render('course/course_detail.html.twig', [
            'controller_name' => 'CourseController',
            'course'=> $cours,
            'coursesLike'=>$coursSimilaires,
            'average' => $moyenne,
            'rating'=> $avisMoyen
        ]);
    }

    /**
     * @Route("/exercises/{id}",name="exercise_detail")
     */

    public function exerciseDetail(Exercise $exercise)
    {
        return $this->render('course/course_exercise_detail.html.twig', [
            'controller_name' => 'CourseController',
            'exercise'=> $exercise
        ]);
    }


}
