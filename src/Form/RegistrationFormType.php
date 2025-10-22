<?php

namespace App\Form;

use App\Dto\Auth\RegistrationDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Имя',
                'attr' => ['placeholder' => 'Введите ваше имя']
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Фамилия',
                'required' => false,
                'attr' => ['placeholder' => 'Введите вашу фамилию (необязательно)']
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => ['placeholder' => 'Введите ваш email адрес']
            ])
            ->add('phone', TelType::class, [
                'label' => 'Номер телефона',
                'required' => false,
                'attr' => ['placeholder' => 'Введите ваш номер телефона (необязательно)']
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'Пароль',
                    'attr' => [
                        'placeholder' => 'Введите ваш пароль',
                        'autocomplete' => 'new-password',
                    ]
                ],
                'second_options' => [
                    'label' => 'Повторите пароль',
                    'attr' => [
                        'placeholder' => 'Повторите ваш пароль',
                        'autocomplete' => 'new-password',
                    ]
                ],
                'invalid_message' => 'Пароли должны совпадать',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RegistrationDto::class,
        ]);
    }
}