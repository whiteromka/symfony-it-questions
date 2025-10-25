<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SiteController extends AbstractController
{
    #[Route('/', name: 'site_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('site/index.html.twig', [
            'data' => 'some data',
        ]);
    }

    #[Route('/page2', name: 'page2', methods: ['GET'])]
    public function page2(): Response
    {
        return $this->render('site/page2.html.twig', [
            'data' => 'some data',
        ]);
    }

    #[Route('/some-js', name: 'some-js', methods: ['GET'])]
    public function test(): Response
    {
        return $this->render('site/some-js.html.twig', [
            'data' => 'some data',
        ]);
    }
}