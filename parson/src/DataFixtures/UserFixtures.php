<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Service\UploaderHelper;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends BaseFixture
{

    private static $images=[
        'ens1.jpg','ens2.jpg','ens3.jpg','etu1.jpg','etu2.jpg','etu3.jpg','etu4.jpg','etu5.jpg'
    ];

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;
    /**
     * @var UploaderHelper
     */
    private $uploaderHelper;

    public function __construct(UserPasswordEncoderInterface $encoder,UploaderHelper $uploaderHelper)
    {

        $this->encoder = $encoder;
        $this->uploaderHelper = $uploaderHelper;
    }

    protected function loadData(ObjectManager $manager)
    {
        /**
         *  On a choisit au début de remplir la bdd par 1 admin
         *  & 3 proffesseurs
         *  & 10 étudiants
         *  Soit au total : 14 utilisateur
         */

        // Créer l'admin içi
        $this->createUsersOfRole($manager,"ROLE_ADMIN",1,"admin@esi.dz");

        // Créer 3 profs
        $this->createUsersOfRole($manager,"ROLE_ENS",3,null);

        // Créer 10 étudiants
        $this->createUsersOfRole($manager,"ROLE_ETU",10,null);


    }

    /**
     * This function is helpful for creating n user with specific role !
     * @param ObjectManager $manager
     * @param string $role
     * @param int $n
     * @param $email
     */

    public function createUsersOfRole(ObjectManager $manager,string $role,int $n,$email)
    {
        $this->createMany(User::class,$n,function (User $user, $i) use ($role, $email) {
            $sexe=$this->faker->boolean;
            $sexe_text=$sexe ? "male" :"female";
            $imgUrl=null;

            /**
             * On crée un admin à la premeiere execution ! & 4 utilisateurs simples
             */
            if ($email)
            {
                $user->setEmail($email)
                    ->setPassword($this->encoder->encodePassword($user,'admin'));
                $this->setReference("admin",$user);

            }
            else{
                if ($role=="ROLE_ENS")
                {
                    $user->setEmail('ens'.$i.'@mail.com');
                    $this->setReference("ens$i",$user);
                    $img="ens$i.jpg";
                    $imgUrl=$this->fakeUploadImg($img,$this->uploaderHelper,'users');


                }
                else{
                    $user->setEmail('student'.$i.'@mail.com');
                    $this->setReference("etu$i",$user);
                    if($i<=5)
                    {
                        $img="etu$i.jpg";
                        $imgUrl=$this->fakeUploadImg($img,$this->uploaderHelper,'users');
                    }
                }
                $user->setPassword($this->encoder->encodePassword($user,'123'));

            }


            $user->setAddress($this->faker->streetAddress)
                ->setImgUrl($imgUrl)
                ->setPhone($this->faker->e164PhoneNumber)
                ->setFullName($this->faker->name($sexe_text))
                ->setSexe($sexe)
                ->setRoles(array($role))
                ->setCreatedAt($this->faker->dateTimeBetween('-1 months','-1 seconds'));

        });

        $manager->flush();
    }



}
