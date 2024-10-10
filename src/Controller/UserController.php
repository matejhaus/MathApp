<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('admin/users/add', name: 'add_users')]
    public function add(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_admin_entity', ['entity' => 'users']);
        }

        return $this->render('admin/add.html.twig', [
            'form' => $form->createView(),
            'entity' => 'users',
        ]);
    }

    #[Route('admin/users/edit/{id}', name: 'edit_users')]
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_admin_entity', ['entity' => 'users']);
        }

        return $this->render('admin/edit.html.twig', [
            'form' => $form->createView(),
            'entity' => 'users',
            'data'=> $user,
        ]);
    }

    #[Route('admin/users/{id}/delete', name: 'delete_users')]
    public function delete(int $id, Request $request): Response
    {
        $user = $this->entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        if ($request->isMethod('POST')) {
            $this->entityManager->remove($user);
            $this->entityManager->flush();

            return new JsonResponse(['success' => true, 'message' => 'Uživatel byl úspěšně smazán.']);
        }

        return $this->render('admin/delete.html.twig', [
            'data' => $user,
            'entity' => 'users',
        ]);
    }
}
