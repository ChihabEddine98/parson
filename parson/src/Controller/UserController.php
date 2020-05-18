<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\User;
use App\Entity\UserCourse;
use App\Repository\UserCourseRepository;
use App\Repository\UserRepository;
use App\Service\UploaderHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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

    /**
     * @Route("/user/edit_img",name="edit_user_img")
     */
    public function editUserImg(Request $request, UploaderHelper $uploaderHelper, EntityManagerInterface $manager)
    {
        /** @var UploadedFile $upImgFile */
        $upImgFile = $request->files->get('user_imgFile');
        if ($upImgFile) {
            $newImgName = $uploaderHelper->uploadUserImg($upImgFile);
            $user = $this->getUser();
            $user->setImgUrl($newImgName);
            $manager->persist($user);
            $manager->flush();
        }

        return $this->redirectToRoute('user_profile', [
            'id' => $user->getId()
        ]);
    }

    /**
     * @Route("/user/edit",name="edit_user_info")
     */
    public function editUserInfo(Request $request, EntityManagerInterface $manager, UserRepository $repo)
    {
        $data = json_decode($request->getContent(), true);
        // Edit !
        if ($data['id']) {
            $user = $repo->findOneBy(["id" => $data['id']]);

            if ($data['addr']) {
                $user->setAddress($data['addr']);
            }
            if ($data['phone']) {
                $user->setPhone($data['phone']);
            }

            $manager->persist($user);
        } // New user !
        else {
            $user = new User();
            $user->setRoles(['ROLE_ENS'])
                ->setPhone($data['phone'])
                ->setAddress($data['addr'])
                ->setFullName($data['name'])
                ->setEmail($data['email'])
                ->setPassword($data['mdp'])
                ->setSexe(1);

            dd($user);
        }

        $manager->flush();

        return new JsonResponse(array('result' => true));
    }

    /**
     * @Route("/admin/new_ens",name="new_ens")
     */
    public function addEns(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $data = json_decode($request->getContent(), true);

        // New user !

        if ($data['nom'] && $data['email'] && $data['mdp']) {
            $user = new User();
            $user->setRoles(['ROLE_ENS'])
                ->setPhone($data['phone'])
                ->setAddress($data['addr'])
                ->setFullName($data['nom'])
                ->setEmail($data['email'])
                ->setPassword($encoder->encodePassword($user, $data['mdp']))
                ->setSexe(1);
            $manager->persist($user);
            $manager->flush();

            return new JsonResponse(array('result' => true));
        }


        return new JsonResponse(array('result' => false));

    }

    /**
     * @Route("/user/edit_role",name="edit_user_role")
     */
    public
    function editUserRole(Request $request, EntityManagerInterface $manager, UserRepository $repo)
    {
        $data = json_decode($request->getContent(), true);
        $user = $repo->findOneBy(["id" => $data['id']]);
        if ($data['roles']) {
            $user->setRoles(array($data['roles']));
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success','Role modifié avec success !');
            return new JsonResponse(array('result' => true));
        }

        return new JsonResponse(array('result' => false));


    }

    /**
     * @Route("/user/delete",name="delete_user")
     */
    public
    function deleteUser(Request $request, EntityManagerInterface $manager, UserRepository $repo)
    {
        $data = json_decode($request->getContent(), true);
        $user = $repo->findOneBy(["id" => $data['id']]);
        if ($user) {
            $manager->remove($user);
            $manager->flush();

            return new JsonResponse(array('result' => true));
        }


        return new JsonResponse(array('result' => false));


    }

    /**
     * @Route("/register_to_course/{id}",name="register_course")
     */
    public
    function userRegister2Course(Request $request, EntityManagerInterface $manager, Course $course, UserCourseRepository $repo)
    {
        $u_c = new UserCourse();
        $u_c->setCourse($course);
        $u_c->setUser($this->getUser());
        $manager->persist($u_c);
        $manager->flush();

        $this->addFlash('success', 'Super ! maintenant vous faites partie de ce cours . ');

        return $this->redirectToRoute('course_detail', ["id" => $course->getId()]);
    }

}
