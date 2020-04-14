<?php

namespace App\DataFixtures;

use App\Entity\Course;
use App\Entity\Exercise;
use Doctrine\Common\Persistence\ObjectManager;

class ExerciseFixtures extends BaseFixture
{
    protected function loadData(ObjectManager $manager)
    {

        $this->createMany(Exercise::class,20,function (Exercise $exercise,$i){
            $exercise->setTitle($this->faker->sentence)
                     ->setMark($this->faker->randomFloat(2,0,5))
                     ->setType($this->faker->boolean(20) ? "normal" : "parson")
                     ->setQuestion($this->faker->sentence(8,true))
                     ->setSolution($this->faker->sentences($this->faker->numberBetween(6,12),false))
                     ->setCourse($this->getRandomRef(Course::class))
                     ->setCreatedAt($this->faker->dateTimeBetween('-1 months','-1 seconds'));


        });

        $manager->flush();
    }
}
