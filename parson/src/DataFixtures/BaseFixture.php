<?php
/**
 * Created by PhpStorm.
 * User: Chihab
 * Date: 10/04/2020
 * Time: 17:20
 */

namespace App\DataFixtures;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\Config\Definition\Exception\Exception;

abstract  class BaseFixture extends Fixture
{

    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @var Generator
     */
    protected $faker;

    private $refIndex=[];

    abstract protected function loadData(ObjectManager $manager);

    public function load(ObjectManager $manager)
    {
        $this->manager=$manager;
        $this->faker=Factory::create();


        $this->loadData($manager);
    }

    protected function createMany(string $className,int $count,callable $cb)
    {

        for ($i=1;$i<=$count;$i++)
        {
            $entity=new $className();
            // içi on peut remplir les champs de notre objet !
            // trop sympa !
            $cb($entity,$i);

            $this->manager->persist($entity);

            // ça génere des couple par exemple (course_1,cours(id=1))
            // sympa pour faire les relations ( foreign keys )
            $this->addReference($className.'_'.$i,$entity);
        }


    }


    protected function getRandomRef(string $className)
    {
        if (!isset($this->refIndex[$className]))
        {
            $this->refIndex[$className]=[];

            foreach ($this->referenceRepository->getReferences() as $key => $ref){

                if(strpos($key,$className.'_'===0))
                {
                    $this->refIndex[$className]=$key;
                }
            }
        }

        if (empty($this->refIndex[$className]))
        {
            throw new Exception(sprintf('Aucune Réference trouvée pour "%s"',$className));

        }

        $randomRefKey=$this->faker->randomElement($this->refIndex[$className]);

        return $this->getReference($randomRefKey);
    }

}