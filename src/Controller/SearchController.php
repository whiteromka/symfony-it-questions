<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;


class SearchController extends AbstractController
{
    #[Route('/search/index', name: 'app_search', methods: ['GET'])]
    public function random(Request $request): JsonResponse
    {
//        if ($this->isCsrfTokenValid('delete'.$question->getId(), $request->getPayload()->getString('_token'))) {
//            $entityManager->remove($question);
//            $entityManager->flush();
//        }

        $query = $request->query->get('query');
        $results = $this->findSearchResults($query);
        $html = $this->renderView('search/_results.html.twig', [
            'results' => $results,
            'query' => $query
        ]);

        return $this->json(['html' => $html]);
    }
}
