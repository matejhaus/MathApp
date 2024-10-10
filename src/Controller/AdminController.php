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

    #[Route('/admin/{entity}', name: 'app_admin_entity')]
    public function showEntity(
        string $entity,
        ThemeRepository $themeRepository,
        ExampleRepository $exampleRepository,
        UserRepository $userRepository,
        QuotesRepository $quotesRepository
    ): Response {
        $data = [];

        switch ($entity) {
            case 'themes':
                $data = $themeRepository->findAllThemes();
                break;
            case 'examples':
                $data = $exampleRepository->findAll();
                break;
            case 'users':
                $data = $userRepository->findAll();
                break;
            case 'quotes':
                $data = $quotesRepository->findAll();
                break;
            default:
                throw $this->createNotFoundException('Entity not found.');
        }

        return $this->render('admin/entity.html.twig', [
            'data' => $data,
            'entity' => $entity
        ]);
    }
}
