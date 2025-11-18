<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/users-skill', name: 'users_skill', methods: ['GET'])]
    public function userSkill(): Response
    {
            return $this->render('user/index.html.twig', [
            'data' => 'vue js',
        ]);
    }
}
