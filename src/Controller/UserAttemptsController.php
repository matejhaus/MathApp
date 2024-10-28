<?php

namespace App\Controller;

use App\Entity\UserAttempts;
use App\Entity\UserStatistics;
use App\Form\UserAttemptsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserAttemptsController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('admin/userAttempts/add', name: 'add_userAttempts')]
    public function add(Request $request): Response
    {
        $userAttempts = new UserAttempts();
        $form = $this->createForm(UserAttemptsType::class, $userAttempts);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($userAttempts);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_admin_entity', ['entity' => 'userAttempts']);
        }

        return $this->render('admin/add.html.twig', [
            'form' => $form->createView(),
            'entity' => 'userAttempts',
        ]);
    }

    #[Route('/admin/userStatistics/edit/{id}', name: 'edit_userAttempts')]
    public function edit(Request $request, UserAttempts $userAttempts): Response
    {
        $form = $this->createForm(UserAttemptsType::class, $userAttempts, [
            'disabled_fields' => ['user', 'theme'],
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($userAttempts);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_admin_entity', ['entity' => 'userAttempts']);
        }

        return $this->render('admin/edit.html.twig', [
            'form' => $form->createView(),
            'entity' => 'userAttempts',
            'data'=> $userAttempts,
        ]);
    }

    #[Route('admin/userStatistics/{id}/delete', name: 'delete_userAttempts')]
    public function delete(int $id, Request $request): Response
    {
        $userAttempts = $this->entityManager->getRepository(UserAttempts::class)->find($id);

        if (!$userAttempts) {
            throw $this->createNotFoundException('theme not found');
        }

        if ($request->isMethod('POST')) {
            $this->entityManager->remove($userAttempts);
            $this->entityManager->flush();

            return new JsonResponse(['success' => true, 'message' => 'Statistiky byly smazány.']);
        }

        return $this->render('admin/delete.html.twig', [
            'data' => $userAttempts,
            'entity' => 'userAttempts',
        ]);
    }
}
