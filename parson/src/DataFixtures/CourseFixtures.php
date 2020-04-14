<?php

namespace App\DataFixtures;

use App\Entity\Course;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class CourseFixtures extends BaseFixture implements DependentFixtureInterface
{
    protected function loadData(ObjectManager $manager)
    {

        $this->createMany(Course::class,10,function (Course $course,$i){
            $course->setTitle($this->faker->sentence)
                   ->setCategory($this->faker->word)
                   ->setTimeNeeded($this->faker->randomFloat(2,8,50))
                   ->setDescription($this->faker->text)
                   ->setAuthor($this->getRandomRef(User::class))
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
           User::class,
       ];
    }
}
