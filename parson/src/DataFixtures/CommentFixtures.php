<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Course;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CommentFixtures extends BaseFixture implements DependentFixtureInterface
{
    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(Comment::class,30,function (Comment $comment,$i){
            $comment->setUser($this->getRandomRef(User::class))
                -> setCourse($this->getRandomRef(Course::class))
                ->setDescription($this->faker->text)
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
            UserFixtures::class
        ];
    }
}
