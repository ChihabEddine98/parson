<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixtures extends BaseFixture
{

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(User::class,3,function (User $user,$i){
            $sexe=$this->faker->boolean;
            $sexe_text=$sexe ? "male" :"female";
            $user->setEmail('user'.$i.'@mail.com')
                -> setPassword("123")
                ->setFullName($this->faker->name($sexe_text))
                ->setSexe($sexe)
                ->setCreatedAt($this->faker->dateTimeBetween('-1 months','-1 seconds'));

        });

        $manager->flush();
    }
}
