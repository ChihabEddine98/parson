<?php

namespace App\Form;

use App\Entity\Course;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class CourseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',null,[
                'label'=>false,
            ])
            ->add('description',null,[
                'label'=>false
            ])
            ->add('category',null,[
                'label'=>false
            ])
            ->add('timeNeeded',null,[
                'label'=>false
            ])
            ->add('imgFile',FileType::class,[
                'mapped'=>false,
                'required'=>false,
                'label'=>false,
                'constraints'=>[
                    new Image([
                        'maxSize'=>'5M'
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Course::class,
        ]);
    }
}
