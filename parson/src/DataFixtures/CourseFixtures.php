<?php

namespace App\DataFixtures;

use App\Entity\Course;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class CourseFixtures extends BaseFixture implements DependentFixtureInterface
{
    /**
     * @var UserRepository
     */
    private $userRepo;

    public function __construct(UserRepository $userRepo)
    {

        $this->userRepo = $userRepo;
    }

    protected function loadData(ObjectManager $manager)
    {
        // Au début il faut ajouter un enseignant aléatoirement pour etre l'auteur de chaque cours !

        $this->createMany(Course::class,10,function (Course $course,$i){
            $course->setTitle($this->faker->sentence)
                   ->setCategory($this->faker->word)
                   ->setTimeNeeded($this->faker->randomFloat(2,8,50))
                   ->setDescription($this->faker->text(400))
                   ->setCreatedAt($this->faker->dateTimeBetween('-1 months','-1 seconds'));

                // Car au début les ens ont des id entre  2 et 4 !
                $profs=$this->userRepo->findByRole("ROLE_ENS");
                $course->setAuthor($this->faker->randomElement($profs));

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
       ];
    }
}
