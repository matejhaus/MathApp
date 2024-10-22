<?php

namespace App\Controller;

use App\Repository\QuotesRepository;
use App\Repository\ThemeRepository;
use App\Repository\UserStatisticsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(ThemeRepository $themeRepository, QuotesRepository $quotesRepository, UserStatisticsRepository $userStatistics, Security $security): Response
    {
        $user = $security->getUser();

        $themes = $themeRepository->findAllThemes();
        $quotes = $quotesRepository->findBy([], ['position' => 'ASC']);

        if ($user) {
            $statsCount = $userStatistics->countStatisticsByUser($user);
        } else {
            $statsCount = null;
        }

        return $this->render('main/index.html.twig', [
            'quotes' => $quotes,
            'themes' => $themes,
            'statsCount' => $statsCount
        ]);
    }
}
