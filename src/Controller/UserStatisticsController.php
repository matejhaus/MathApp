<?php

namespace App\Controller;

use App\Entity\UserStatistics;
use App\Form\UserStatisticsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserStatisticsController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('admin/userStatistics/add', name: 'add_userStatistics')]
    public function add(Request $request): Response
    {
        $userStatistics = new UserStatistics();
        $form = $this->createForm(UserStatisticsType::class, $userStatistics);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($userStatistics);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_admin_entity', ['entity' => 'userStatistics']);
        }

        return $this->render('admin/add.html.twig', [
            'form' => $form->createView(),
            'entity' => 'userStatistics',
        ]);
    }

    #[Route('/admin/userStatistics/edit/{id}', name: 'edit_userStatistics')]
    public function edit(Request $request, UserStatistics $userStatistics): Response
    {
        $form = $this->createForm(UserStatisticsType::class, $userStatistics, [
            'disabled_fields' => ['user', 'theme'],
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($userStatistics);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_admin_entity', ['entity' => 'userStatistics']);
        }

        return $this->render('admin/edit.html.twig', [
            'form' => $form->createView(),
            'entity' => 'userStatistics',
            'data'=> $userStatistics,
        ]);
    }

    #[Route('admin/userStatistics/{id}/delete', name: 'delete_userStatistics')]
    public function delete(int $id, Request $request): Response
    {
        $userStatistics = $this->entityManager->getRepository(UserStatistics::class)->find($id);

        if (!$userStatistics) {
            throw $this->createNotFoundException('theme not found');
        }

        if ($request->isMethod('POST')) {
            $this->entityManager->remove($userStatistics);
            $this->entityManager->flush();

            return new JsonResponse(['success' => true, 'message' => 'Statistiky byly smazány.']);
        }

        return $this->render('admin/delete.html.twig', [
            'data' => $userStatistics,
            'entity' => 'userStatistics',
        ]);
    }
}
