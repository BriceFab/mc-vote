<?php

namespace App\Form\User;

use App\Entity\User;
use App\Form\Fields\SubmitField;
use App\Form\Fields\UserPasswordField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'user.username',
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 5,
                        'max' => 20,
                    ]),
                    new Regex([
                        'pattern' => "/^[a-zA-Z_\d]+$/",
                        'message' => 'Votre pseudonyme doit contenir uniquement des lettres, chiffres et underscore (_)'
                    ])
                ],
                'trim' => true,
            ])
            ->add('email', EmailType::class, [
                'label' => 'user.email',
                'required' => true,
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => UserPasswordField::class,
                'first_options' => ['label' => 'user.password'],
                'second_options' => ['label' => 'user.password.confirm'],
                'translation_domain' => 'messages',
                'invalid_message' => 'The values do not match.',
                'required' => true,
                'mapped' => false
            ])
            ->add('submit', SubmitField::class, [
                'label' => 'action.register'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
