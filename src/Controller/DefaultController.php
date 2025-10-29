<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DefaultController extends AbstractController
{
    #[Route('/default', name: 'app_default')]
    public function index(Request $request): Response
    {
        $name = $request->query->get('name');
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'name' => $name,
        ]);
    }
}
