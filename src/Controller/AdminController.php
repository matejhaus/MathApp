<?php

namespace App\Controller;

use App\Repository\QuotesRepository;
use App\Repository\ThemeRepository;
use App\Repository\ExampleRepository;
use App\Repository\UserAttemptsRepository;
use App\Repository\UserRepository;
use App\Repository\UserStatisticsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Utils\ExampleCsvImporter;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(
        ThemeRepository $themeRepository,
        ExampleRepository $exampleRepository,
        UserRepository $userRepository,
        QuotesRepository $quotesRepository,
        UserStatisticsRepository $userStatisticsRepository,
        UserAttemptsRepository $userAttemptsRepository
    ): Response {
        $counts = [
            [
                'count' => $exampleRepository->count([]),
                'name' => 'Příklady',
                'route' => 'examples'
            ],
            [
                'count' => $themeRepository->count([]),
                'name' => 'Témata',
                'route' => 'themes'
            ],
            [
                'count' => $userRepository->count([]),
                'name' => 'Uživatelé',
                'route' => 'users'
            ],
            [
                'count' => $quotesRepository->count([]),
                'name' => 'Citáty',
                'route' => 'quotes'
            ],
            [
                'count' => $userStatisticsRepository->count([]),
                'name' => 'Statistiky uživatelů',
                'route' => 'userStatistics'
            ],
            [
                'count' => $userAttemptsRepository->count([]),
                'name' => 'Pokusy uživatelů',
                'route' => 'userAttempts'
            ]
        ];

        return $this->render('admin/index.html.twig', [
            'counts' => $counts
        ]);
    }


    #[Route('/admin/example-import', name: 'app_admin_example_import')]
    public function exampleImport(Request $request, ExampleCsvImporter $exampleCsvImporter): Response
    {
        if ($request->isMethod('POST') && $request->files->get('csv_file')) {
            $file = $request->files->get('csv_file');
            try {
                $exampleCsvImporter->importCsvFile($file);
                $this->addFlash('success', 'CSV soubor byl úspěšně importován.');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Chyba při importu souboru: ' . $e->getMessage());
            }
        }

        return $this->redirectToRoute('app_admin');
    }

    #[Route('/admin/entity/{entity}/{sortBy}/{sortOrder}', name: 'app_admin_entity', defaults: ['sortBy' => 'id', 'sortOrder' => 'ASC'])]
    public function showEntity(
        string $entity,
        string $sortBy,
        string $sortOrder,
        ThemeRepository $themeRepository,
        ExampleRepository $exampleRepository,
        UserRepository $userRepository,
        QuotesRepository $quotesRepository,
        UserStatisticsRepository $userStatisticsRepository,
        UserAttemptsRepository $userAttemptsRepository
    ): Response {

        $sortOrder = strtoupper($sortOrder);
        if (!in_array($sortOrder, ['ASC', 'DESC'])) {
            throw new \InvalidArgumentException("Neplatná hodnota pro třídění: $sortBy");
        }

        switch ($entity) {
            case 'themes':
                $data = $themeRepository->findBy([], [$sortBy => $sortOrder]);
                break;
            case 'examples':
                $data = $exampleRepository->findBy([], [$sortBy => $sortOrder]);
                break;
            case 'users':
                $data = $userRepository->findBy([], [$sortBy => $sortOrder]);
                break;
            case 'quotes':
                $data = $quotesRepository->findBy([], [$sortBy => $sortOrder]);
                break;
            case 'userStatistics':
                $data = $userStatisticsRepository->findBy([], [$sortBy => $sortOrder]);
                break;
            case 'userAttempts':
                $data = $userAttemptsRepository->findBy([], [$sortBy => $sortOrder]);
                break;
            default:
                throw $this->createNotFoundException('Entity not found.');
        }

        return $this->render('admin/entity.html.twig', [
            'data' => $data,
            'entity' => $entity,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder
        ]);
    }
}
