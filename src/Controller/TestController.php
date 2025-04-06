<?php
namespace App\Controller;

use App\Entity\Grade;
use App\Entity\Theme;
use App\Entity\UserAttempts;
use App\Entity\UserStatistics;
use App\Form\PasswordFormType;
use App\Repository\ThemeRepository;
use App\Repository\ExampleRepository;
use App\Repository\UserAttemptsRepository;
use App\Repository\UserStatisticsRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class TestController extends AbstractController
{
    private CsrfTokenManagerInterface $csrfTokenManager;
    private $entityManager;

    public function __construct(CsrfTokenManagerInterface $csrfTokenManager,EntityManagerInterface $entityManager)
    {
        $this->csrfTokenManager = $csrfTokenManager;
        $this->entityManager = $entityManager;
    }

    #[Route('/theme/test/check-password/{id}', name: 'check_password')]
    public function checkPassword(Request $request, Theme $theme): Response
    {
        $form = $this->createForm(PasswordFormType::class);
        $form->handleRequest($request);
        $testSettings = $theme->getTestSettings();

        if (empty($testSettings->getAccessCode())) {
            return new JsonResponse(['redirect' => $this->generateUrl('test_show', ['id' => $theme->getId()])]);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form->get('password')->getData();
            $testSettings = $theme->getTestSettings();
            if ($password === $testSettings->getAccessCode()) {
                return $this->redirectToRoute('test_show', ['id' => $theme->getId()]);
            } else {
                $this->addFlash('error', 'Incorrect password. Please try again.');
            }
        }

        return new Response(
            $this->renderView('Components/PasswordModal.html.twig', [
                'password_form' => $form->createView(),
                'theme' => $theme
            ])
        );
    }


    #[Route('/test/{id}', name: 'test_show')]
    public function show(int $id, Request $request, ThemeRepository $themeRepository, ExampleRepository $exampleRepository,
                         UserStatisticsRepository $userStatisticsRepository,UserAttemptsRepository $userAttemptsRepository
                        ,EntityManagerInterface $entityManager
    ): Response
    {
        $theme = $themeRepository->find($id);
        $testSettings = $theme->getTestSettings();

        if (!$theme) {
            throw $this->createNotFoundException('Theme not found');
        }

        $examples = $exampleRepository->findBy(
            ['theme' => $theme],
            $testSettings->isRandomOrder() ? [] : ['id' => 'ASC'],
            $testSettings->getNumberOfQuestions()
        );

        if ($testSettings->isRandomOrder()) {
            shuffle($examples);
        }

        $examples = array_slice($examples, 0, $testSettings->getNumberOfQuestions());


        $results = [];
        $userAnswers = [];
        $score = [];

        $user = $this->getUser();

        if ($request->isMethod('POST')) {

            $csrfToken = $request->request->get('_csrf_token');
            if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('test_submit', $csrfToken))) {
                throw new BadRequestHttpException('Neplatný CSRF token.');
            }

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
                ['user' => $user, 'theme' => $theme],
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
                $oldestAttempt->setTheme($theme);
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

            $percentage = ($correctCount + $incorrectCount) > 0
                ? $correctCount / ($correctCount + $incorrectCount) * 100
                : 0;

            $grade = 5; // výchozí známka (nejhorší)

            if ($percentage >= $testSettings->getGrade1Percentage()) {
                $grade = 1;
            } elseif ($percentage >= $testSettings->getGrade2Percentage()) {
                $grade = 2;
            } elseif ($percentage >= $testSettings->getGrade3Percentage()) {
                $grade = 3;
            } elseif ($percentage >= $testSettings->getGrade4Percentage()) {
                $grade = 4;
            }

            $score = [
                'correctCount' => $correctCount,
                'incorrectCount' => $incorrectCount,
                'percentage' => $percentage,
                'grade' => $grade,
            ];

            $gradeToSave = new Grade();
            $gradeToSave->setGrade($grade);
            $gradeToSave->setCreatedAt(new \DateTimeImmutable());
            $gradeToSave->setUser($this->getUser());
            $gradeToSave->setTheme($theme);
            $this->entityManager->persist($gradeToSave);
            $this->entityManager->flush();
        }

        return $this->render('test/index.html.twig', [
            'title' => 'Test' . ' ' . $theme->getName(),
            'examples' => $examples,
            'theme' => $theme,
            'results' => $results,
            'time' => $testSettings->getTimeLimitInMinutes(),
            'user_answers' => $userAnswers,
            'score' => $score
        ]);
    }
}


