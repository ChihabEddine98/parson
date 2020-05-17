<?php

namespace App\DataFixtures;

use App\Entity\Course;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\UploaderHelper;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\File;

class CourseFixtures extends BaseFixture implements DependentFixtureInterface
{

    private static $images=[
            '1.jpg','2.jpeg','3.jpg','4.jpg','5.jpg','6.jpg','7.jpg','8.png','9.jpg','10.jpg',
        ];
    private static $categories=[
            'informatique','mathematiques','science'
        ];
    /**
     * @var UploaderHelper
     */
    private $uploaderHelper;


    public function __construct(UploaderHelper $uploaderHelper)
    {

        $this->uploaderHelper = $uploaderHelper;
    }

    /**
     * @param ObjectManager $manager
     */
    protected function loadData(ObjectManager $manager)
    {
        // Au début il faut ajouter un enseignant aléatoirement pour etre l'auteur de chaque cours !

        $this->createMany(Course::class,10,function (Course $course,$i) use ($manager) {

            $img=self::$images[$i-1];
            $imgUrl=$this->fakeUploadImg($img,$this->uploaderHelper,'cours');

            $course->setTitle($this->faker->sentence)
                   ->setImgUrl($imgUrl)
                   ->setCategory($this->faker->randomElement(self::$categories))
                   ->setTimeNeeded($this->faker->randomFloat(2,8,50))
                   ->setDescription($this->faker->text(400))
                   ->setCreatedAt($this->faker->dateTimeBetween('-1 months','-1 seconds'));

                // Car au début les ens ont des id entre  2 et 4 !
                //$profs=$this->userRepo->findByRole("ROLE_ENS");
                 $randRef='ens'.$this->faker->numberBetween(1,3);
//                $profs= $manager->getRepository(User::class)->findByRole("ROLE_ENS");
                //$profs=$this->entityManager->getRepository(User::class)->findByRole("ROLE_ENS");
                $course->setAuthor($this->getReference($randRef));

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
