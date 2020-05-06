<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\Exercise;
use App\Entity\UserCourse;
use App\Form\CourseType;
use App\Repository\CourseRepository;
use App\Repository\UserCourseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CourseController extends BaseController
{
    /**
     * @Route("/courses", name="courses")
     */
    public function index()
    {
        $repo=$this->getDoctrine()->getRepository(Course::class);
        $courses=$repo->findAll();

        return $this->render('course/course_list.html.twig', [
            'title' => ' Tout Les cours',
            'courses'=>$courses
        ]);
    }


    /**
     * @Route("/courses/new",name="new_course")
     * @IsGranted("ROLE_ENS")
     */
    public function new(EntityManagerInterface $manager,Request $request)
    {
        $form=$this->createForm(CourseType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $course=$form->getData();
            $course->setAuthor($this->getUser());

            $manager->persist($course);
            $manager->flush();

            return $this->redirectToRoute('my_created_courses');
        }

        return $this->render('course/course_new.html.twig',[
            'courseForm'=>$form->createView()
        ]);
    }



    /**
     * @Route("/courses/me",name="my_courses")
     * @IsGranted("ROLE_USER")
     */

    public function mesCours()
    {

        $courses=$this->getUser()->getRegistredInCourses();
        return $this->render('course/course_my.html.twig', [
            'courses' => $courses
        ]);
    }

    /**
     * @Route("/courses/by_me",name="my_created_courses")
     * @IsGranted("ROLE_ENS")
     */

    public function mesCoursCrees()
    {
        $courses=$this->getUser()->getCreatedCourses();
        return $this->render('course/course_list.html.twig', [
            'title'=> "Les cours que j'encadre ",
            'courses' => $courses
        ]);
    }
    /**
     * @Route("/notes/me",name="my_marks")
     * @IsGranted("ROLE_USER")
     */

    public function mesNotes(UserCourseRepository $repoScore)
    {
        $courses=$this->getUser()->getRegistredInCourses();

        // Calculate avergae Rating !


        $moyenne=$repoScore->findAverageByUser($this->getUser());


        return $this->render('course/course_notes.html.twig', [
            'courses' => $courses,
            'average'=>$moyenne
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


    /**
     * @Route("/exo/parson/submit",name="exo_parson_submit")
     */
    public function submitExoParson(Request $request)
    {
        $data=$request->request->get('items');

        dd(json_decode($request->getContent()));

    }

}
