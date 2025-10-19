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
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Имя',
                'constraints' => [
                    new NotBlank(['message' => 'Пожалуйста, введите ваше имя',]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Имя должно содержать не менее {{ limit }} символов',
                        'max' => 50,
                        'maxMessage' => 'Имя не должно быть длиннее {{ limit }} символов',
                    ]),
                ],
                'attr' => ['placeholder' => 'Введите ваше имя',]
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Фамилия',
                'required' => false,
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Фамилия должна содержать не менее {{ limit }} символов',
                        'max' => 50,
                        'maxMessage' => 'Фамилия не должна быть длиннее {{ limit }} символов',
                    ]),
                ],
                'attr' => ['placeholder' => 'Введите вашу фамилию (необязательно)',]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'constraints' => [
                    new NotBlank(['message' => 'Пожалуйста, введите ваш email',]),
                    new Email(['message' => 'Пожалуйста, введите корректный email адрес',]),
                ],
                'attr' => ['placeholder' => 'Введите ваш email адрес',]
            ])
            ->add('phone', TelType::class, [
                'label' => 'Номер телефона',
                'required' => false,
                'constraints' => [
                    new Regex([
                        'pattern' => '/^\+?[0-9\s\-\(\)]{10,}$/',
                        'message' => 'Пожалуйста, введите корректный номер телефона',
                    ]),
                ],
                'attr' => ['placeholder' => 'Введите ваш номер телефона (необязательно)',]
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'Пароль',
                    'constraints' => [
                        new NotBlank(['message' => 'Пожалуйста, введите пароль',]),
                        new Length([
                            'min' => 3,
                            'minMessage' => 'Пароль должен содержать не менее {{ limit }} символов',
                            'max' => 10,
                            'maxMessage' => 'Пароль не должен быть длиннее {{ limit }} символов',
                        ]),
                    ],
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