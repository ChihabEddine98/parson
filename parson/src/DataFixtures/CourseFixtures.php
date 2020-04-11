<?php

namespace App\DataFixtures;

use App\Entity\Course;
use Doctrine\Common\Persistence\ObjectManager;

class CourseFixtures extends BaseFixture
{
    protected function loadData(ObjectManager $manager)
    {

        $this->createMany(Course::class,10,function (Course $course,$i){
            $course->setTitle('Cours_'.$i)
                   ->setCategory('categorie')
                   ->setTimeNeeded($this->faker->numberBetween(8,50))
                   ->setDescription('contenu provisoire '.$i);

        });

        $manager->flush();
    }
}
