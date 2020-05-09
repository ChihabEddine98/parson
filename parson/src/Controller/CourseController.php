<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Course;
use App\Entity\Exercise;
use App\Entity\UserCourse;
use App\Form\CourseType;
use App\Repository\CommentRepository;
use App\Repository\CourseRepository;
use App\Repository\ExerciseRepository;
use App\Repository\UserCourseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
        $repo = $this->getDoctrine()->getRepository(Course::class);
        $courses = $repo->findAll();

        return $this->render('course/course_list.html.twig', [
            'title' => ' Tout Les cours',
            'courses' => $courses
        ]);
    }


    /**
     * @Route("/courses/new",name="new_course")
     * @IsGranted("ROLE_ENS")
     */
    public function new(EntityManagerInterface $manager, Request $request)
    {
        $form = $this->createForm(CourseType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $course = $form->getData();
            $course->setAuthor($this->getUser());

            $manager->persist($course);
            $manager->flush();

            return $this->redirectToRoute('my_created_courses');
        }

        return $this->render('course/course_new.html.twig', [
            'courseForm' => $form->createView()
        ]);
    }


    /**
     * @Route("/courses/me",name="my_courses")
     * @IsGranted("ROLE_USER")
     */

    public function mesCours()
    {

        $courses = $this->getUser()->getRegistredInCourses();
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
        $courses = $this->getUser()->getCreatedCourses();
        return $this->render('course/course_list.html.twig', [
            'title' => "Les cours que j'encadre ",
            'courses' => $courses
        ]);
    }

    /**
     * @Route("/notes/me",name="my_marks")
     * @IsGranted("ROLE_USER")
     */

    public function mesNotes(UserCourseRepository $repoScore)
    {
        $courses = $this->getUser()->getRegistredInCourses();

        // Calculate avergae Rating !


        $moyenne = $repoScore->findAverageByUser($this->getUser());


        return $this->render('course/course_notes.html.twig', [
            'courses' => $courses,
            'average' => $moyenne
        ]);
    }

    /**
     * @Route("/results/ens_me",name="ens_results")
     * @IsGranted("ROLE_ENS")
     */

    public function ensResults(UserCourseRepository $repoScore)
    {
        $createdCourses=$this->getUser()->getCreatedCourses()->getValues();
        // Calculate avergae Rating !

        $results=$repoScore->findBy(['course'=>$createdCourses]);
        $somme=0;
        $sommeAvis=0;
        foreach ($results as $result)
        {
            $somme+= $result->getScore();
            $sommeAvis+=$result->getRate();
        }
        $moyenne=count($results) ? $somme/count($results):0;
        $avisMoyen=count($results) ? $sommeAvis/count($results): 0;


        return $this->render('course/ens_results.html.twig', [
            'results'=> $results,
            'average' => $moyenne,
            'rate' => $avisMoyen
        ]);
    }

    /**
     * @Route("/courses/{id}",name="course_detail")
     */

    public function courseDetail(Course $cours,
                                 CommentRepository $repoComment,
                                 CourseRepository $repo,
                                 UserCourseRepository $repoScore,
                                 PaginatorInterface $paginator,
                                 Request $request)
    {
        $coursSimilaires = $repo->findByCategoryAndNotThisId($cours->getCategory(), $cours->getId());
        $moyenne = $repoScore->findAverageByCourse($cours);
        $avisMoyen = $repoScore->findAverageRateByCourse($cours);
        $query=$repoComment->findByCourse($cours);

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            3 /*limit per page*/
        );

        return $this->render('course/course_detail.html.twig', [
            'course' => $cours,
            'coursesLike' => $coursSimilaires,
            'average' => $moyenne,
            'rating' => $avisMoyen,
            'pagination'=>$pagination
        ]);
    }

    /**
     * @Route("/exercises/{id}",name="exercise_detail")
     */

    public function exerciseDetail(Exercise $exercise)
    {
        return $this->render('course/course_exercise_detail.html.twig', [
            'controller_name' => 'CourseController',
            'exercise' => $exercise,
            'result' => null
        ]);
    }


    public function getMarkFromResults(UserCourse $u_c)
    {
        $results = $u_c->getResults();
        $score = 0;
        foreach ($results as $result) {
            if ($result) {
                $score += floatval(array_values($result)[0]);


            }
        }
        return $score;
    }

    public function changeResult(EntityManagerInterface $manager, UserCourse $u_c, $results, $id, $note)
    {
        $changed = false;
        $results_new = array();
        if ($results) {
            foreach ($results as $key => $value) {
                $r = $results[$key];

                try {
                    // Si l'exo a deja une note précdente
                    if ($r[$id]) {
                        $changed = true;
                        array_push($results_new, [$id => $note]);
                    }
                } catch (\ErrorException $e) {
                    // laisse les autres inchangés !
                    array_push($results_new, $r);
                }


            }
            if (!$changed) {
                // Si l'exo n'a pas deja une note précdente
                array_push($results_new, [$id => $note]);
            }
        } else {
            // La premiere note !
            array_push($results_new, [$id => $note]);

        }


        $u_c->setResults($results_new);
        $u_c->setScore($this->getMarkFromResults($u_c));
        $manager->persist($u_c);
        $manager->flush();
    }

    /**
     * @Route("/exo/parson/submit",name="exo_parson_submit")
     */
    public function submitExoParson(EntityManagerInterface $manager, Request $request, ExerciseRepository $repo, UserCourseRepository $userCourseRepo)
    {
        $data = json_decode($request->getContent(), true);
        $result = true;
        $id = $data['id'];

        $exo = $repo->findOneBy(['id' => $id]);

//        array_push($data['items'],'chihab');

//        $diff= $data['items']===$exo->getSolution();
//        dd($diff);
//        dd();
        $i = 0;
        foreach ($data['items'] as $item) {
            if ($item != $exo->getSolution()[$i]) {
                $result = false;
                $u_c = $userCourseRepo->findOneByUserAndCourse($this->getUser(), $exo->getCourse());
                $results = $u_c->getResults();

                $this->changeResult($manager, $u_c, $results, $exo->getId(), 0);

                return new JsonResponse(array('result' => $result));

            }
            $i++;
        }

        // Si la réponse est vraie !
        $u_c = $userCourseRepo->findOneByUserAndCourse($this->getUser(), $exo->getCourse());
        $results = $u_c->getResults();
        $note=number_format(20/$exo->getCourse()->getExercises()->count(),2);
        $this->changeResult($manager, $u_c, $results, $exo->getId(), $note);
        return new JsonResponse(array('result' => true));

    }


    /**
     * @Route("/course/comment/new",name="new_comment")
     */
    public function saveComment(EntityManagerInterface $manager, Request $request, CourseRepository $repo)
    {
        $data = json_decode($request->getContent(), true);

        $id = $data['id'];
        $course = $repo->findOneBy(['id' => $id]);

        $comment=new Comment();
        $comment->setCourse($course);
        $comment->setDescription($data['comment']);
        $comment->setUser($this->getUser());

        $manager->persist($comment);
        $manager->flush();


        return new JsonResponse(true);

    }


    /**
     * @Route("/course/rate",name="course_rate")
     */
    public function saveRate(EntityManagerInterface $manager, Request $request
        , UserCourseRepository $repo,CourseRepository $courseRepo)
    {
        $data = json_decode($request->getContent(), true);

        $id = $data['id'];

        $course=$courseRepo->findOneBy(["id"=>intval($id)]);

        $result=$repo->findOneByUserAndCourse($this->getUser(),$course);
        $result->setRate(intval($data['stars']));
        $manager->persist($result);
        $manager->flush();

        $avisMoyen = $repo->findAverageRateByCourse($course);



        return new JsonResponse($avisMoyen);

    }

}
