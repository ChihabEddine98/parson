<?php

namespace App\DataFixtures;

use App\Entity\Course;
use App\Entity\User;
use App\Entity\UserCourse;
use App\Repository\UserRepository;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class UserCourseFixtures extends BaseFixture implements DependentFixtureInterface
{
    /**
     * @var UserRepository
     */
    private $userRepo;

    /**
     * UserCourseFixtures constructor.
     * @param UserRepository $userRepo
     */
    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(UserCourse::class,20,function (UserCourse $userCourse,$i){
//            $etudiants= $this->userRepo->findByRole("ROLE_ETU");

            $randRef='etu'.$this->faker->numberBetween(1,10);
            $userCourse->setUser($this->getReference($randRef))
                -> setCourse($this->getRandomRef(Course::class))
                ->setRate($this->faker->randomFloat(2,1,5))
                ->setScore(0)
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
