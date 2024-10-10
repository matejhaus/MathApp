<?php
namespace App\Controller;

use App\Entity\UserAttempts;
use App\Entity\UserStatistics;
use App\Repository\ThemeRepository;
use App\Repository\ExampleRepository;
use App\Repository\UserAttemptsRepository;
use App\Repository\UserStatisticsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TestController extends AbstractController
{
    #[Route('/test/{id}', name: 'test_show')]
    public function show(int $id, Request $request, ThemeRepository $themeRepository, ExampleRepository $exampleRepository,
                         UserStatisticsRepository $userStatisticsRepository,UserAttemptsRepository $userAttemptsRepository
                        ,EntityManagerInterface $entityManager
    ): Response
    {
        $theme = $themeRepository->find($id);

        if (!$theme) {
            throw $this->createNotFoundException('Theme not found');
        }

        $examples = $exampleRepository->findBy(['theme' => $theme]);

        $results = [];
        $userAnswers = [];

        $user = $this->getUser();

        if ($request->isMethod('POST')) {
            $allData = $request->request->all();
            $answers = $allData['answers'] ?? [];

            $correctCount = 0;
            $incorrectCount = 0;

            foreach ($examples as $index => $example) {
                $correctAnswer = $example->getResult();
                $userAnswer = isset($answers[$index]) ? $answers[$index] : '';

                $userAnswers[$index] = $userAnswer;
                $isCorrect = $userAnswer === $correctAnswer;
                if ($isCorrect) {
                    $correctCount++;
                } else {
                    $incorrectCount++;
                }

                $results[$index] = [
                    'question' => $example->getQuestion(),
                    'user_answer' => $userAnswer,
                    'correct_answer' => $correctAnswer,
                    'is_correct' => $isCorrect
                ];
            }

            $userStatistics = $userStatisticsRepository->findOneBy([
                'user' => $user,
                'theme' => $theme,
            ]);

            if (!$userStatistics) {
                $userStatistics = new UserStatistics();
                $userStatistics->setUser($user);
                $userStatistics->setTheme($theme);
                $userStatistics->setCorrectAnswers(0);
                $userStatistics->setIncorrectAnswers(0);
                $userStatistics->setTotalAttempts(0);

                $entityManager->persist($userStatistics);
            }

            $userStatistics->setCorrectAnswers(
                $userStatistics->getCorrectAnswers() + $correctCount
            );
            $userStatistics->setIncorrectAnswers(
                $userStatistics->getIncorrectAnswers() + $incorrectCount
            );
            $userStatistics->setTotalAttempts(
                $userStatistics->getTotalAttempts() + 1
            );

            $userAttempts = $userAttemptsRepository->findBy(
                ['user' => $user],
                ['id' => 'ASC']
            );

            if (count($userAttempts) < 5) {
                $newAttempt = new UserAttempts();
                $newAttempt->setUser($user);
                $newAttempt->setTheme($theme);
                $newAttempt->setCorrectAnswers($correctCount);
                $newAttempt->setIncorrectAnswers($incorrectCount);

                $entityManager->persist($newAttempt);
            } else {
                $oldestAttempt = $userAttempts[0];
                $oldestAttempt->setTopic($theme);
                $oldestAttempt->setCorrectAnswers($correctCount);
                $oldestAttempt->setIncorrectAnswers($incorrectCount);
            }

            $correctAnswers=$userStatistics->getCorrectAnswers();
            $totalAnswers=$userStatistics->getIncorrectAnswers()+$correctAnswers;
            $rate = $totalAnswers > 0 ? ($correctAnswers / $totalAnswers) * 100 : 0;

            switch (true) {
                case ($rate < 10):
                    $userStatistics->setUserLevel("Nováček");
                    break;
                case ($rate < 20):
                    $userStatistics->setUserLevel("Začátečník");
                    break;
                case ($rate < 30):
                    $userStatistics->setUserLevel("Mírně pokročilý");
                    break;
                case ($rate < 40):
                    $userStatistics->setUserLevel("Pokročilý");
                    break;
                case ($rate < 50):
                    $userStatistics->setUserLevel("Velmi pokročilý");
                    break;
                case ($rate < 60):
                    $userStatistics->setUserLevel("Expert");
                    break;
                case ($rate < 70):
                    $userStatistics->setUserLevel("Mistr");
                    break;
                case ($rate < 80):
                    $userStatistics->setUserLevel("Grandmaster");
                    break;
                default:
                    $userStatistics->setUserLevel("Legenda");
                    break;
            }

            $entityManager->flush();
        }

        return $this->render('test/index.html.twig', [
            'title' => 'Test' . ' ' . $theme->getName(),
            'examples' => $examples,
            'theme' => $theme,
            'results' => $results,
            'user_answers' => $userAnswers
        ]);
    }
}


