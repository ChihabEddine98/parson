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
        $this->createMany(User::class,3,function (User $user,$i){
            $sexe=$this->faker->boolean;
            $sexe_text=$sexe ? "male" :"female";
            $user->setEmail('user'.$i.'@mail.com')
                ->setAddress($this->faker->streetAddress)
                ->setPhone($this->faker->e164PhoneNumber)
                ->setPassword($this->encoder->encodePassword($user,'123'))
                ->setFullName($this->faker->name($sexe_text))
                ->setSexe($sexe)
                ->setCreatedAt($this->faker->dateTimeBetween('-1 months','-1 seconds'));

        });

        $manager->flush();
    }
}
