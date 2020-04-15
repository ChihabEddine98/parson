<?php

namespace App\DataFixtures;

use App\Entity\Course;
use App\Entity\User;
use App\Entity\UserCourse;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class UserCourseFixtures extends BaseFixture implements DependentFixtureInterface
{
    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(UserCourse::class,20,function (UserCourse $userCourse,$i){
            $userCourse->setUser($this->getRandomRef(User::class))
                -> setCourse($this->getRandomRef(Course::class))
                ->setRate($this->faker->randomFloat(2,1,5))
                ->setScore($this->faker->randomFloat(1,4,20))
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
            UserFixtures::class,
            CourseFixtures::class,
        ];
    }
}
