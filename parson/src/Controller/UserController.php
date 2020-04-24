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
        $users=$repo->findAll();

        return $this->render('user/user_list.html.twig', [
            'controller_name' => 'UserController',
             'users'=> $users
        ]);
    }

    /**
     * @Route("/users/{id}", name="user_profile")
     */
    public function profile(User $user,UserCourseRepository $repoScore)
    {
        // Calculate avergae Rating !
        $courses=$user->getCreatedCourses();
        $rates=[];
        $somme=0;
        $i=0;
        foreach ($courses as $course)
        {
           $rate=$repoScore->findAverageRateByCourse($course);
           $somme+=$rate;
           $i+=1;
        }
       /* array_map(function ($course) use ($repoScore){
            return $repoScore->findAverageRateByCourse($course);
        },$courses);*/


        //$avisMoyen=array_sum($courses)/count($courses);

         if ($i>0)
         {
             $avisMoyen=$somme/$i;
         }
         else
         {
             $avisMoyen=0;
         }

        return $this->render('user/user_profile.html.twig', [
            'controller_name' => 'UserController',
            'user'=>$user,
            'rating'=> $avisMoyen
        ]);
    }
}
