<?php

namespace App\Controller;

use App\Entity\Grade;
use App\Entity\User;
use App\Entity\UserAttempts;
use App\Entity\UserStatistics;
use App\Form\GradeType;
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

            $userStatistics = $this->entityManager->getRepository(UserStatistics::class)->findBy(['user' => $user]);
            foreach ($userStatistics as $statistic) {
                $this->entityManager->remove($statistic);
            }

            $userAttempts = $this->entityManager->getRepository(UserAttempts::class)->findBy(['user' => $user]);
            foreach ($userAttempts as $attempts) {
                $this->entityManager->remove($attempts);
            }

            $this->entityManager->remove($user);
            $this->entityManager->flush();

            return new JsonResponse(['success' => true, 'message' => 'Uživatel byl úspěšně smazán.']);
        }

        return $this->render('admin/delete.html.twig', [
            'data' => $user,
            'entity' => 'users',
        ]);
    }

    #[Route('admin/grades/add', name: 'add_grades')]
    public function addGrade(Request $request): Response
    {
        $grade = new Grade();
        $form = $this->createForm(GradeType::class, $grade);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($grade);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_admin_entity', ['entity' => 'grades']);
        }

        return $this->render('admin/add.html.twig', [
            'form' => $form->createView(),
            'entity' => 'grades',
        ]);
    }

    #[Route('admin/grades/edit/{id}', name: 'edit_grades')]
    public function editGrade(Request $request, User $user): Response
    {
        $grades = $this->entityManager->getRepository(Grade::class)->findBy(['user' => $user]);

        $forms = [];

        foreach ($grades as $grade) {
            $form = $this->createForm(GradeType::class, $grade);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->entityManager->flush();
                $this->addFlash('success', 'Známky byly úspěšně upraveny.');
            }

            $forms[] = $form->createView();
        }

        return $this->render('admin/edit_grades.html.twig', [
            'user' => $user,
            'forms' => $forms,
        ]);
    }
}
