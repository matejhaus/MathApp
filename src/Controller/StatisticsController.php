<?php

namespace App\Controller;

use App\Repository\UserAttemptsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserStatisticsRepository;
use Symfony\Component\Security\Core\User\UserInterface;

class StatisticsController extends AbstractController
{
    #[Route('/statistics', name: 'app_statistics')]
    public function index(UserStatisticsRepository $userStatisticsRepository,UserAttemptsRepository $userAttemptsRepository, UserInterface $user): Response
    {
        $userStatistics = $userStatisticsRepository->findBy(['user' => $user]);
        $userAttempts =  $userAttemptsRepository->findBy(['user' => $user]);
        $statisticsWithAttempts = [];
        foreach ($userStatistics as $index => $statistic){
            $theme=$statistic->getTheme();
            $attemptsForTheme = array_filter($userAttempts, function ($attempt) use ($theme){
                return $attempt->getTheme() === $theme;
            });

            $correctAnswers=$statistic->getCorrectAnswers();
            $totalAnswers=$statistic->getIncorrectAnswers()+$correctAnswers;
            $statistic->rate = $totalAnswers > 0 ? ($correctAnswers / $totalAnswers) * 100 : 0;

            $statistic->errorRate=($statistic->getIncorrectAnswers()/$totalAnswers)*100;
            $statistic->correctToIncorrectRatio = round(($correctAnswers / ($statistic->getIncorrectAnswers() > 0 ? $statistic->getIncorrectAnswers() : 1)) * 100, 2);
            $statistic->totalAnswers=$totalAnswers;
            $statistic->attempts = $attemptsForTheme;

            if (count($attemptsForTheme) > 1) {
                $lastAttempt = end($attemptsForTheme);
                $secondLastAttempt = prev($attemptsForTheme);

                $lastCorrectAnswers = $lastAttempt->getCorrectAnswers();
                $lastTotalAnswers = $lastAttempt->getCorrectAnswers() + $lastAttempt->getIncorrectAnswers();
                $lastScore = $lastTotalAnswers > 0 ? ($lastCorrectAnswers / $lastTotalAnswers) * 100 : 0;

                $secondLastCorrectAnswers = $secondLastAttempt->getCorrectAnswers();
                $secondLastTotalAnswers = $secondLastAttempt->getCorrectAnswers() + $secondLastAttempt->getIncorrectAnswers();
                $secondLastScore = $secondLastTotalAnswers > 0 ? ($secondLastCorrectAnswers / $secondLastTotalAnswers) * 100 : 0;

                $improvement = $lastScore - $secondLastScore;
                $statistic->improvement = $improvement;
            }

            $statisticsWithAttempts[] = $statistic;
        }

        return $this->render('statistics/index.html.twig', [
            'statistics' => $statisticsWithAttempts,
        ]);
    }
}

