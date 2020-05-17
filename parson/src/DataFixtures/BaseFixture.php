<?php
/**
 * Created by PhpStorm.
 * User: Chihab
 * Date: 10/04/2020
 * Time: 17:20
 */

namespace App\DataFixtures;


use App\Service\UploaderHelper;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;

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

    private $referencesIndex=[];

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
            $this->setReference($className.'_'.$i,$entity);
        }


    }


    protected function getRandomRef(string $className)
    {
        if (!isset($this->referencesIndex[$className])) {
            $this->referencesIndex[$className] = [];
            foreach ($this->referenceRepository->getReferences() as $key => $ref) {
                if (strpos($key, $className.'_') === 0) {
                    $this->referencesIndex[$className][] = $key;
                }
            }
        }
        if (empty($this->referencesIndex[$className])) {
            throw new \Exception(sprintf('Réference introuvable pour : "%s"', $className));
        }
        $randomReferenceKey = $this->faker->randomElement($this->referencesIndex[$className]);
        return $this->getReference($randomReferenceKey);
    }


    protected function fakeUploadImg($img,UploaderHelper $uploaderHelper,$type):string
    {
        $fd=new Filesystem();
        $tmpFile=sys_get_temp_dir().'/'.$img;
        $fd->copy(__DIR__.'/images/'.$type.'/'.$img,$tmpFile,true);
        if ($type=='cours')
        {
            $imgUrl=$uploaderHelper->uploadCourseImg(new File($tmpFile));
        }
        else{
            $imgUrl=$uploaderHelper->uploadUserImg(new File($tmpFile));
        }

        return $imgUrl;
    }

}