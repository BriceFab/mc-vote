<?php

namespace App\Form\User;

use App\Entity\User;
use App\Form\Fields\SubmitField;
use App\Form\Fields\UserPasswordField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('login', EmailType::class, [
                'label' => 'Login',
            ])
            ->add('password', UserPasswordField::class)
            ->add('submit', SubmitField::class, [
                'label' => 'action.register'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
            'csrf_token_id' => 'authenticate',
        ]);
    }
}
