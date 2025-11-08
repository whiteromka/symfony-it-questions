<?php

namespace App\Service;

class MenuService
{
    public function getMenuItems(): array
    {
        return [
            ['name' => 'Случайный вопрос', 'route' => 'question_random', 'icon' => 'fa-list-check'],
            ['name' => 'Вопросы собесов', 'route' => 'question_index', 'icon' => 'fa-list-check'],
            ['name' => 'JS тест', 'route' => 'some_js', 'icon' => 'fa-list-check'],
            ['name' => 'Vue JS тест', 'route' => 'vue_js', 'icon' => 'fa-list-check'],
            ['name' => 'Блог', 'route' => 'blog_index', 'icon' => 'fa-list-check'],
            ['name' => 'Категории Блогов', 'route' => 'blog_category_index', 'icon' => 'fa-list-check'],
            ['name' => 'Теги блогов', 'route' => 'b_tag_index', 'icon' => 'fa-list-check'],
        ];
    }
}