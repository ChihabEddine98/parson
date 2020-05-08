<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\Exercise;
use App\Entity\UserCourse;
use App\Form\CourseType;
use App\Repository\CourseRepository;
use App\Repository\ExerciseRepository;
use App\Repository\UserCourseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
            'exercise'=> $exercise,
            'result'=>null
        ]);
    }


    public function getMarkFromResults(UserCourse $u_c)
    {
        $results=$u_c->getResults();
        $score=0;
        foreach ($results as $result )
        {
            if ($result)
            {
                $score+=floatval(array_values($result)[0]);


            }
        }
        return $score;
    }

    /**
     * @Route("/exo/parson/submit",name="exo_parson_submit")
     */
    public function submitExoParson(EntityManagerInterface $manager,Request $request,ExerciseRepository $repo,UserCourseRepository $userCourseRepo)
    {
        $data=json_decode($request->getContent(),true);
        $result=true;
        $id=$data['id'];

        $exo=$repo->findOneBy(['id'=>$id]);

//        array_push($data['items'],'chihab');

//        $diff= $data['items']===$exo->getSolution();
//        dd($diff);
//        dd();


        $i=0;
        foreach ( $data['items'] as $item)
        {
            if ($item != $exo->getSolution()[$i])
            {
                $result=false;
                $changed=false;
                $u_c=$userCourseRepo->findOneByUserAndCourse($this->getUser(),$exo->getCourse());
                $results=$u_c->getResults();
                $results_new=array();
                if ($results)
                {
                    foreach ( $results as $key=>$value)
                    {
                        $r=$results[$key];

                        try {
                            if ($r[$exo->getId()])
                            {
                                $changed=true;
                                $r[$exo->getId()]=3;
                                array_push($results_new,[$exo->getId() => 3]);
                            }
                        }catch (\ErrorException $e)
                        {
                            array_push($results_new,$r);
                        }


                    }
                    if (!$changed)
                    {
                        array_push($results_new,[$exo->getId() => 7]);
                    }
                }
                else
                {
                    array_push($results,[$exo->getId() => 8]);
                    array_push($results_new,[$exo->getId() => 8]);

                }


                $u_c->setResults($results_new);
                $u_c->setScore($this->getMarkFromResults($u_c));
                $manager->persist($u_c);
                $manager->flush();
                return new JsonResponse(array('result'=>$result));
            }
            $i++;
        }

        return new JsonResponse(array('result'=>true));

    }

}
