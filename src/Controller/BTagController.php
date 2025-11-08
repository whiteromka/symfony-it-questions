<?php

namespace App\Controller;

use App\Entity\BTag;
use App\Form\BTagType;
use App\Repository\BTagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/btag')]
final class BTagController extends AbstractController
{
    #[Route(name: 'b_tag_index', methods: ['GET'])]
    public function index(BTagRepository $bTagRepository): Response
    {
        return $this->render('b_tag/index.html.twig', [
            'b_tags' => $bTagRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'b_tag_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $bTag = new BTag();
        $form = $this->createForm(BTagType::class, $bTag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($bTag);
            $entityManager->flush();

            return $this->redirectToRoute('b_tag_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('b_tag/new.html.twig', [
            'b_tag' => $bTag,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'b_tag_show', methods: ['GET'])]
    public function show(BTag $bTag): Response
    {
        return $this->render('b_tag/show.html.twig', [
            'b_tag' => $bTag,
        ]);
    }

    #[Route('/{id}/edit', name: 'b_tag_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, BTag $bTag, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BTagType::class, $bTag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('b_tag_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('b_tag/edit.html.twig', [
            'b_tag' => $bTag,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'b_tag_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, BTag $bTag, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($bTag);
        $entityManager->flush();
        return $this->redirectToRoute('b_tag_index', [], Response::HTTP_SEE_OTHER);
    }
}
