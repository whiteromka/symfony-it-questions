<?php

namespace App\Form;

use App\Entity\Question;
use App\Entity\QuestionCategory;
use App\Enum\QuestionStatus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Заголовок',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Введите заголовок вопроса'
                ]
            ])
            ->add('text', TextareaType::class, [
                'label' => 'Текст',
                'required' => false,
                'attr' => [
                    'class' => 'form-control summernote-basic',
                    'rows' => 10
                ],
            ])
            ->add('difficulty', null, [
                'label' => 'Сложность',
                'attr' => ['class' => 'form-control']
            ])
            ->add('questionCategory', EntityType::class, [
                'label' => 'Категория',
                'class' => QuestionCategory::class,
                'choice_label' => 'name',
                'placeholder' => 'Выберите категорию',
                'attr' => ['class' => 'form-select']
            ])
            ->add('status', EnumType::class, [
                'label' => 'Статус',
                'class' => QuestionStatus::class,
                'choice_label' => fn(QuestionStatus $status) => $status->getValue(),
                'attr' => ['class' => 'form-select']
            ])
            ->add('createdAt', null, [
                'label' => 'Создано',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}