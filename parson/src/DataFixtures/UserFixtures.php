<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixtures extends BaseFixture
{

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(User::class,3,function (User $user,$i){
            $sexe=$this->faker->boolean(50);
            $sexe_text=$sexe ? "male" :"female";
            $user->setEmail('user'.$i.'@mail.com')
                ->setFullName($this->faker->name($sexe_text))
                ->setSexe($sexe)
                ->setCreatedAt($this->faker->dateTimeBetween('-100 days','today'));

        });

        $manager->flush();
    }
}
