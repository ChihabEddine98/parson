<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserCourseRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/users", name="user_list")
     */
    public function index(UserRepository $repo)
    {
        $users = $repo->findAll();

        return $this->render('user/user_list.html.twig', [
            'controller_name' => 'UserController',
            'users' => $users
        ]);
    }

    /**
     * @Route("/users/{id}", name="user_profile")
     */
    public function profile(User $user, UserCourseRepository $repoScore)
    {
        // Calculate avergae Rating !
        $courses = $user->getCreatedCourses();
        $somme = 0;
        $noteSomme = 0;
        $heureSomme = 0;
        $userSomme = 0;
        $i = 0;
//        foreach ($courses as $course)
//        {
//           $rate=$repoScore->findAverageRateByCourse($course);
//           $note=$repoScore->findAverageByCourse($course);
//           $heureSomme+=$course->getTimeNeeded();
//           $userSomme+=count($course->getUsers());
//           $somme+=$rate;
//           $noteSomme+=$note;
//           $i+=1;
//        }
//       /* array_map(function ($course) use ($repoScore){
//            return $repoScore->findAverageRateByCourse($course);
//        },$courses);*/
//
//
//        //$avisMoyen=array_sum($courses)/count($courses);
//
//         if ($i>0)
//         {
//             $avisMoyen=$somme/$i;
//             $moyenne=$noteSomme/$i;
//         }
//         else
//         {
//             $avisMoyen=0;
//             $moyenne=0;
//         }

        $avisMoyen = $repoScore->findAverageRateByUser($user);
        $moyenne = $repoScore->findAverageByUser($user);

        return $this->render('user/user_profile.html.twig', [
            'controller_name' => 'UserController',
            'user' => $user,
            'rating' => $avisMoyen,
            'average' => $moyenne,
            'nbUsers' => $userSomme,
            'nbHours' => $heureSomme
        ]);
    }
}
