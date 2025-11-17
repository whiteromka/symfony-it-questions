<?php

namespace App\Controller;

use App\Dto\Input\QuestionRequestDto;
use App\Entity\Question;
use App\Factory\QuestionFactory;
use App\Form\QuestionType;
use App\Repository\QuestionRepository;
use App\Service\QuestionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/question')]
final class QuestionController extends AbstractController
{
    #[Route('/', name: 'question_index', methods: ['GET'])]
    public function index(QuestionRepository $questionRepository): Response
    {
        return $this->render('question/index.html.twig', [
            'questions' => $questionRepository->findAllOrderedById(),
        ]);
    }

    #[Route('/random', name: 'question_random', methods: ['GET'])]
    public function random(QuestionService $randomQuestionService): Response
    {
        $question = $randomQuestionService->getRandomQuestion();
        if (!is_null($question)) {
            $randomQuestionService->createQuestionHistory($question->getId());
        }
        return $this->render('question/show.html.twig', [
            'question' => $question,
        ]);
    }

    #[Route('/{id}', name: 'question_show', requirements: ['id' => '\d+'], defaults: ['id' => 1], methods: ['GET'])]
    public function show(Question $question): Response
    {
        return $this->render('question/show.html.twig', [
            'question' => $question,
        ]);
    }

    #[Route('/create', name: 'question_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        QuestionFactory $questionFactory
    ): Response {
        $questionDto = new QuestionRequestDto();
        $form = $this->createForm(QuestionType::class, $questionDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $question = $questionFactory->createFromDto($questionDto);
            $entityManager->persist($question);
            $entityManager->flush();
            return $this->redirectToRoute('question_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('question/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'question_edit', requirements: ['id' => '\d+'], defaults: ['id' => 1], methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Question $question,
        EntityManagerInterface $entityManager,
        QuestionFactory $questionFactory
    ): Response {
        $questionDto = $questionFactory->createDtoFromEntity($question);
        $form = $this->createForm(QuestionType::class, $questionDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $questionFactory->createFromDto($questionDto, $question);
            $entityManager->flush();
            return $this->redirectToRoute('question_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('question/edit.html.twig', [
            'question' => $question,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'question_delete', requirements: ['id' => '\d+'], methods: ['POST', 'GET'])]
    public function delete(Request $request, Question $question, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($question);
        $entityManager->flush();
        return $this->redirectToRoute('question_index', [], Response::HTTP_SEE_OTHER);
    }
}
