<?php

namespace App\Controller;

use App\Repository\QuotesRepository;
use App\Repository\ThemeRepository;
use App\Repository\ExampleRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(ThemeRepository $themeRepository): Response
    {
        $themes = $themeRepository->findAllThemes();

        return $this->render('admin/index.html.twig', [
            'themes' => $themes,
        ]);
    }

    #[Route('/admin/{entity}/{sortBy}/{sortOrder}', name: 'app_admin_entity', defaults: ['sortBy' => 'id', 'sortOrder' => 'ASC'])]
    public function showEntity(
        string $entity,
        string $sortBy,
        string $sortOrder,
        ThemeRepository $themeRepository,
        ExampleRepository $exampleRepository,
        UserRepository $userRepository,
        QuotesRepository $quotesRepository
    ): Response {

        switch ($entity) {
            case 'themes':
                $data = $themeRepository->findBy([], [$sortBy => $sortOrder]);
                break;
            case 'examples':
                $data = $exampleRepository->findBy([], [$sortBy => $sortOrder]);
                break;
            case 'users':
                $data = $userRepository->findBy([], [$sortBy => $sortOrder]);
                break;
            case 'quotes':
                $data = $quotesRepository->findBy([], [$sortBy => $sortOrder]);
                break;
            default:
                throw $this->createNotFoundException('Entity not found.');
        }

        return $this->render('admin/entity.html.twig', [
            'data' => $data,
            'entity' => $entity,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder
        ]);
    }
}
