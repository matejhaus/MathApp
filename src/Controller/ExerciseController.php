<?php

namespace App\Controller;

use App\Form\ExerciseType;
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
    public function index(int $id, ThemeRepository $themeRepository, Request $request): Response
    {
        $theme = $themeRepository->find($id);

        if (!$theme) {
            throw $this->createNotFoundException('Téma nebylo nalezeno.');
        }

        $form = $this->createForm(ExerciseType::class, null, [
            'theme_id' => $theme->getId(),
            'theme_name'=>$theme->getName()
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $minValue = $data['min_value'];
            $maxValue = $data['max_value'];
            $numberOfExamples = $data['number_of_examples'];
            $difficulty = $data['difficulty'];

            $this->generator = $this->createGenerator($theme->getName());
            $examples = $this->generator->generate($minValue, $maxValue, $numberOfExamples, $difficulty);

            return $this->render('exercise/exercise.html.twig', [
                'examples' => $examples,
                'theme' => $theme->getName(),
                'difficulty' => $difficulty,
            ]);
        }

        return $this->render('exercise/index.html.twig', [
            'theme' => $theme,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/exercise/{theme}/test', name: 'app_exercise_test')]
    public function showEntity(string $theme, Request $request, ThemeRepository $themeRepository): Response
    {
        $themeEntity = $themeRepository->findOneBy(['name' => $theme]);
        $form = $this->createForm(ExerciseType::class, null, [
            'theme_id' => $themeEntity->getId(),
            'theme_name' => $themeEntity->getName()
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $minValue = $data['min_value'];
            $maxValue = $data['max_value'];
            $numberOfExamples = $data['number_of_examples'];
            $difficulty = $data['difficulty'];

            $this->generator = $this->createGenerator($theme);
            $examples = $this->generator->generate($minValue, $maxValue, $numberOfExamples, $difficulty);

            return $this->render('exercise/exercise.html.twig', [
                'examples' => $examples,
                'theme' => $theme,
                'difficulty' => $difficulty,
            ]);
        }

        return $this->redirectToRoute('app_exercise', ['id' => $themeEntity->getId()]);
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


