<?php

namespace App\Controller;

use App\Repository\QuotesRepository;
use App\Repository\ThemeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(ThemeRepository $themeRepository, QuotesRepository $quotesRepository): Response
    {
        $themes = $themeRepository->findAllThemes();
        $quotes = $quotesRepository->findBy([], ['position' => 'DESC']);

        return $this->render('main/index.html.twig', [
            'quotes' => $quotes,
            'themes' => $themes,
        ]);
    }
}
