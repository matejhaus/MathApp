<?php

namespace App\Controller;

use App\Generators\AdditionSubtractionGenerator;
use App\Generators\DivisionGenerator;
use App\Generators\EquationGenerator;
use App\Generators\InequalityGenerator;
use App\Generators\MultiplicationGenerator;
use App\Repository\ThemeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class ExerciseController extends AbstractController
{
    private ?object $generator = null;

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


