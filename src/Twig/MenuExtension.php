<?php

namespace App\Twig;

use App\Service\MenuService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MenuExtension extends AbstractExtension
{
    public function __construct(private readonly MenuService $menuService) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_menu_items', [$this->menuService, 'getMenuItems']),
        ];
    }
}