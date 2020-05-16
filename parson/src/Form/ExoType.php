<?php

namespace App\Form;

use App\Entity\Exercise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',null,[
                'label'=>false,
                'attr'=>[
                    'placeholder'=>' Titre de l\'exo'
                ]
            ])
            ->add('question',null,[
                'label'=>false,
                'attr'=>[
                    'placeholder'=>' Question '
                ]
            ])
            ->add('type',null,[
                'label'=>false,
                'attr'=>[
                    'placeholder'=>' Type de l\'exo'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Exercise::class,
        ]);
    }
}
