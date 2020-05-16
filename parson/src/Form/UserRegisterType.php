<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserRegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fullName',null,[
                'label'=>false
            ])
            ->add('email',null,[
                'label'=>false
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'label'=> false,
                'invalid_message' => 'Les mots de passe doivent etre identiques',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => ' '],
                'second_options' => ['label' => '(choisissez un puissant !)'],])

//            ->add('password',PasswordType::class,[
//                'label'=>false
//            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
