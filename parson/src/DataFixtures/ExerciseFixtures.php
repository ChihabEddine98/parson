<?php

namespace App\DataFixtures;

use App\Entity\Course;
use App\Entity\Exercise;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ExerciseFixtures extends BaseFixture implements DependentFixtureInterface
{
    protected function loadData(ObjectManager $manager)
    {

        $this->createMany(Exercise::class,20,function (Exercise $exercise,$i){
            $instructions=array();
            for ($j=0;$j<8;$j++)
            {
                array_push($instructions,"instruction $j");
            }

            $exercise->setTitle($this->faker->sentence)
                     ->setMark($this->faker->randomFloat(2,0,5))
                     ->setType($this->faker->boolean(20) ? "normal" : "parson")
                     ->setQuestion($this->faker->sentence(8,true))
                     ->setSolution($instructions)
                     ->setCourse($this->getRandomRef(Course::class))
                     ->setCreatedAt($this->faker->dateTimeBetween('-1 months','-1 seconds'));


        });

        $manager->flush();
    }


    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return class-string[]
     */
    public function getDependencies()
    {
       return [
         CourseFixtures::class,
       ];
    }
}
