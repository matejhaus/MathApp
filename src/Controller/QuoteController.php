<?php

namespace App\Controller;

use App\Entity\Quotes;
use App\Form\QuoteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuoteController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('admin/quote/add', name: 'add_quotes')]
    public function add(Request $request): Response
    {
        $quote = new Quotes();
        $form = $this->createForm(QuoteType::class, $quote);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($quote);
            $this->entityManager->flush();

            return $this->redirectToRoute('edit_quotes', ['id' => $quote->getId()]);
        }

        return $this->render('admin/add.html.twig', [
            'form' => $form->createView(),
            'entity' => 'quotes',
        ]);
    }

    #[Route('admin/quote/edit/{id}', name: 'edit_quotes')]
    public function edit(Request $request, Quotes $quote): Response
    {
        $form = $this->createForm(QuoteType::class, $quote);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($quote);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_admin_entity', ['entity' => 'quotes']);
        }

        return $this->render('admin/edit.html.twig', [
            'form' => $form->createView(),
            'entity' => 'quotes',
            'data' => $quote,
        ]);
    }

    #[Route('admin/quote/{id}/delete', name: 'delete_quotes')]
    public function delete(int $id, Request $request): Response
    {
        $quote = $this->entityManager->getRepository(Quotes::class)->find($id);

        if (!$quote) {
            throw $this->createNotFoundException('Quote not found');
        }

        if ($request->isMethod('POST')) {
            $this->entityManager->remove($quote);
            $this->entityManager->flush();

            return new JsonResponse(['success' => true, 'message' => 'Citát byl úspěšně smazán.']);
        }

        return $this->render('admin/delete.html.twig', [
            'data' => $quote,
            'entity' => 'quotes',
        ]);
    }
}
