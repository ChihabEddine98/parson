<?php

namespace App\DataFixtures;

use App\Entity\Course;
use Doctrine\Common\Persistence\ObjectManager;

class CourseFixtures extends BaseFixture
{
    protected function loadData(ObjectManager $manager)
    {

        $this->createMany(Course::class,10,function (Course $course,$i){
            $course->setTitle('Cours numéro '.$i)
                   ->setCategory('categorie')
                   ->setTimeNeeded(rand(10,50))
                   ->setDescription('contenu provisoire '.$i);

        });

        $manager->flush();
    }
}
