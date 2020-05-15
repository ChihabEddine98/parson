<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserRegisterType;
use App\Repository\UserCourseRepository;
use App\Repository\UserRepository;
use App\Security\LoginFormAuthenticator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request,UserPasswordEncoderInterface $encoder,
            GuardAuthenticatorHandler $guardHandler,LoginFormAuthenticator $formAuthenticator): Response
    {

        $form=$this->createForm(UserRegisterType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            /** @var User $user*/
            $user=$form->getData();
            $user->setPassword($encoder->encodePassword(
                $user,
                $user->getPassword()
            ));

            $manager=$this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $formAuthenticator,
                'main'
            );
        }


        return $this->render('security/register.html.twig',[
            'registerForm'=>$form->createView()
        ]);
    }


    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/admin/results",name="admin_results")
     * @IsGranted("ROLE_ADMIN")
     */

    public function adminResults(UserCourseRepository $repoScore,UserRepository $userRepo)
    {

        $users=$userRepo->findByRole('ROLE_ENS');
        $allResults=[];
        foreach ($users as $user)
        {
            $createdCourses=$user->getCreatedCourses()->getValues();
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

            array_push($allResults,[$results,$user]);

        }


        return $this->render('course/list/admin_results.html.twig', [
            'all_results'=> $allResults,
            'average' => $moyenne,
            'rate' => $avisMoyen
        ]);
    }

}
