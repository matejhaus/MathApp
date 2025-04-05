<?php

namespace App\Controller;

use App\Repository\UserStatisticsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LeaderBoardController extends AbstractController
{
    #[Route('/leaderboard/{id}', name: 'leader_board')]
    public function index(int $id, UserStatisticsRepository $userStatisticsRepository): Response
    {
        $themeStatistics = $userStatisticsRepository->findTop10ByTheme($id);
        return $this->render('leader_board/index.html.twig', [
            'themeStats' => $themeStatistics
        ]);
    }

}
