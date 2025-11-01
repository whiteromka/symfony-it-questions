<?php

namespace App\Form;

use App\Entity\Blog;
use App\Entity\BlogCategory;
use App\Entity\BTag;
use App\Form\DataTransformer\TagTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlogType extends AbstractType
{
    public function __construct(
        private readonly TagTransformer $transformer
    )
    {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Заголовок'])
            ->add('text', TextareaType::class, ['label' => 'Текст'])
            ->add('blogCategory', EntityType::class, [
                'label' => 'Категория',
                'class' => BlogCategory::class,
                'choice_label' => 'name',
                'required' => false
            ])
            ->add('tags', EntityType::class, [
                'label' => 'Теги',
                'class' => BTag::class,
                'choice_label' => 'name',
                'multiple' => true,
                'required' => false,
                'placeholder' => 'Выберите теги',
                'attr' => ['class' => 'tags-select'],
                //'expanded' => true, // Отображать как чекбоксы
            ]);

        // $builder->get('tags')->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Blog::class,
        ]);
    }
}
