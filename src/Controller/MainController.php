<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserStatistics;
use App\Repository\QuotesRepository;
use App\Repository\ThemeRepository;
use App\Repository\UserRepository;
use App\Repository\UserStatisticsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(ThemeRepository $themeRepository, QuotesRepository $quotesRepository, UserInterface $user, UserStatisticsRepository $userStatistics): Response
    {
        $themes = $themeRepository->findAllThemes();
        $quotes = $quotesRepository->findBy([], ['position' => 'ASC']);
        $statsCount = $userStatistics->countStatisticsByUser($user);

        return $this->render('main/index.html.twig', [
            'quotes' => $quotes,
            'themes' => $themes,
            'statsCount' => $statsCount
        ]);
    }
}
