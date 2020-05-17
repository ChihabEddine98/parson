<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends BaseFixture
{

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {

        $this->encoder = $encoder;
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
     * @param string $email
     */

    public function createUsersOfRole(ObjectManager $manager,string $role,int $n,string $email)
    {
        $this->createMany(User::class,$n,function (User $user, $i) use ($role, $email) {
            $sexe=$this->faker->boolean;
            $sexe_text=$sexe ? "male" :"female";

            /**
             * On crée un admin à la premeiere execution ! & 4 utilisateurs simples
             */
            if ($email)
            {
                $user->setEmail($email)
                    ->setPassword($this->encoder->encodePassword($user,'admin'));

            }
            else{
                if ($role=="ROLE_ENS")
                {
                    $user->setEmail('ens'.$i.'@mail.com');
                }
                else{
                    $user->setEmail('student'.$i.'@mail.com');
                }

            }
            $user->setAddress($this->faker->streetAddress)
                ->setPassword($this->encoder->encodePassword($user,'123'))
                ->setPhone($this->faker->e164PhoneNumber)
                ->setFullName($this->faker->name($sexe_text))
                ->setSexe($sexe)
                ->setRoles(array($role))
                ->setCreatedAt($this->faker->dateTimeBetween('-1 months','-1 seconds'));

        });

        $manager->flush();
    }



}
