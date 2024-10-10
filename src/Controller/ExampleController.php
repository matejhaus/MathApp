<?php

namespace App\Controller;

use App\Entity\Example;
use App\Form\ExampleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class ExampleController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('admin/example/add', name: 'add_examples')]
    public function add(Request $request): Response
    {
        $example = new Example();
        $form = $this->createForm(ExampleType::class, $example);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($example);
            $this->entityManager->flush();

            return $this->redirectToRoute('edit_examples', ['id' => $example->getId()]);
        }

        return $this->render('admin/add.html.twig', [
            'form' => $form->createView(),
            'entity' => 'examples',
        ]);
    }

    #[Route('admin/examples/edit/{id}', name: 'edit_examples')]
    public function edit(Request $request, Example $example): Response
    {
        $form = $this->createForm(ExampleType::class, $example);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($example);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_admin_entity', ['entity' => 'examples']);
        }

        return $this->render('admin/edit.html.twig', [
            'form' => $form->createView(),
            'entity' => 'examples',
            'data'=> $example,
        ]);
    }

    #[Route('admin/examples/{id}/delete', name: 'delete_examples')]
    public function delete(int $id, Request $request): Response
    {
        $example = $this->entityManager->getRepository(Example::class)->find($id);

        if (!$example) {
            throw $this->createNotFoundException('example not found');
        }

        if ($request->isMethod('POST')) {
            $this->entityManager->remove($example);
            $this->entityManager->flush();

            return new JsonResponse(['success' => true, 'message' => 'Uživatel byl úspěšně smazán.']);
        }

        return $this->render('admin/delete.html.twig', [
            'data' => $example,
            'entity' => 'examples',
        ]);
    }
}
