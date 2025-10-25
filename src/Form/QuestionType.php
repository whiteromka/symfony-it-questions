<?php

namespace App\Form;

use App\Dto\Input\QuestionRequestDto;
use App\Entity\QuestionCategory;
use App\Entity\User;
use App\Form\DataTransformer\EntityIdTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionType extends AbstractType
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Заголовок',
                'required' => true,
            ])
            ->add('text', TextareaType::class, [
                'label' => 'Текст',
                'required' => false,
            ])
            ->add('difficulty', IntegerType::class, [
                'label' => 'Сложность',
            ])
            ->add('categoryId', EntityType::class, [
                'label' => 'Категория',
                'class' => QuestionCategory::class,
                'choice_label' => 'name',
                'placeholder' => 'Выберите категорию',
                'attr' => ['class' => 'form-select']
            ])
            ->add('authorId', EntityType::class, [
                'label' => 'Автор',
                'class' => User::class,
                'choice_label' => 'name',
                'placeholder' => 'Выберите автора',
                'attr' => ['class' => 'form-select']
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Статус',
                'choices' => [
                    'Активный' => 1,
                    'Неактивный' => 0,
                ],
                'required' => false,
                'attr' => ['class' => 'form-select']
            ]);


        $builder->get('categoryId')->addModelTransformer(
            new EntityIdTransformer($this->entityManager, QuestionCategory::class)
        );

        $builder->get('authorId')->addModelTransformer(
            new EntityIdTransformer($this->entityManager, User::class)
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => QuestionRequestDto::class,
        ]);
    }
}