<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/skill')]
class SkillController extends AbstractController
{
    #[Route('/skill', name: 'skill', methods: ['GET'])]
    public function skill(): Response
    {
        return $this->render('skill/skill.html.twig');
    }
}