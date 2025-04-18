<?php

namespace App\Form;

use App\Entity\Question;
use App\Entity\QuestionCategory;
use App\Enum\QuestionStatus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, ['label' => 'Заголовок'])
            ->add('text', TextareaType::class, [
                'label' => 'Текст',
                'required' => false,
                'attr' => [
                    'data-controller' => 'html-editor'
                ],
            ])
            ->add('difficulty', null, ['label' => 'Сложность'])
            ->add('questionCategory', EntityType::class, [
                'label' => 'Категория',
                'class' => QuestionCategory::class,
                'choice_label' => 'name',
                'placeholder' => 'Выберите категорию'
            ])
            ->add('status', EnumType::class, [
                'label' => 'Статус',
                'class' => QuestionStatus::class,
                'choice_label' => fn(QuestionStatus $status) => $status->getLabel()
            ])
            ->add('createdAt', null, [
                'label' => 'Создано',
                'widget' => 'single_text',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}
