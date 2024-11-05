<?php

namespace App\Controller;

use App\Repository\ThemeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ThemesController extends AbstractController
{
    #[Route('/themes', name: 'app_themes')]
    public function index(ThemeRepository $themeRepository): Response
    {
        $themes = $themeRepository->findAllThemes();

        return $this->render('themes/index.html.twig', [
            'themes' => $themes,
        ]);
    }
}
