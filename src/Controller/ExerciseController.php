<?php

namespace App\Controller;

use App\Generators\AdditionSubtractionGenerator;
use App\Generators\DivisionGenerator;
use App\Generators\EquationGenerator;
use App\Generators\InequalityGenerator;
use App\Generators\MultiplicationGenerator;
use App\Generators\PerimeterGenerator;
use App\Generators\TimeProblemGenerator;
use App\Generators\WordProblemGenerator;
use App\Repository\ThemeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class ExerciseController extends AbstractController
{
    private ?object $generator = null;

    private $csrfTokenManager;

    public function __construct(CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->csrfTokenManager = $csrfTokenManager;
    }

    private function createGenerator(string $theme)
    {
        switch ($theme) {
            case 'Rovnice':
                return new EquationGenerator();
            case 'Sčítání a odčítání':
                return new AdditionSubtractionGenerator();
            case 'Dělení':
                return new DivisionGenerator();
            case 'Násobení':
                return new MultiplicationGenerator();
            case 'Nerovnice':
                return new InequalityGenerator();
            case 'Obvod':
                return new PerimeterGenerator();
            case 'Slovní úlohy':
                return new WordProblemGenerator();
            case 'Čas':
                return new TimeProblemGenerator();
            default:
                throw new \InvalidArgumentException('Neznámé téma');
        }
    }

    #[Route('/exercise/{id}', name: 'app_exercise')]
    public function index(int $id, ThemeRepository $themeRepository): Response
    {
        $theme = $themeRepository->find($id);
        return $this->render('exercise/index.html.twig', [
            'theme' => $theme,
        ]);
    }

    #[Route('/exercise/{theme}/test', name: 'app_exercise_test')]
    public function showEntity(string $theme, Request $request): Response
    {
        $minValue = $request->query->getInt('min_value', 1);
        $maxValue = $request->query->getInt('max_value', 10);
        $numberOfExamples = $request->query->getInt('number_of_examples', 1);
        $difficulty = $request->query->get('difficulty', 'easy');

        $this->generator = $this->createGenerator($theme);

        $examples = $this->generator->generate($minValue, $maxValue, $numberOfExamples, $difficulty);

        return $this->render('exercise/exercise.html.twig', [
            'examples' => $examples,
            'theme' => $theme,
            'difficulty' => $difficulty,
        ]);
    }

    #[Route('/solve', name: 'app_solve', methods: 'POST')]
    public function solve(Request $request): JsonResponse
    {
        $csrfToken = $request->request->get('_csrf_token');
        if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('solve', $csrfToken))) {
            return $this->json(['error' => 'Neplatný CSRF token.'], 403);
        }

        $equation = $request->request->get('equation', '');
        $difficulty = $request->request->get('difficulty', '');
        $theme = $request->request->get('theme', '');

        if (empty($equation)) {
            return $this->json(['error' => 'Rovnice není zadána.'], 400);
        }

        if (empty($difficulty)) {
            return $this->json(['error' => 'Není vybraná náročnost'], 400);
        }

        if (empty($theme)) {
            return $this->json(['error' => 'Není vybrané téma'], 400);
        }

        if ($this->generator === null) {
            $this->generator = $this->createGenerator($theme);
        }

        $solutionData = $this->generator->solve($equation, $difficulty);

        return $this->json($solutionData);
    }
}


