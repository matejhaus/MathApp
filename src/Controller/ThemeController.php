<?php

namespace App\Controller;

use App\Entity\Example;
use App\Entity\Theme;
use App\Form\ThemeType;
use App\Repository\ThemeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ThemeController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/theme/{id}', name: 'theme_show')]
    public function show(int $id, ThemeRepository $themeRepository): Response
    {
        $theme = $themeRepository->find($id);
        $count = $theme->getExamplesCount();

        if (!$theme) {
            throw $this->createNotFoundException('Theme not found');
        }

        return $this->render('theme/theme.html.twig', [
            'theme' => $theme,
            'examplesCount' => $count
        ]);
    }

    #[Route('admin/theme/add', name: 'add_themes')]
    public function add(Request $request): Response
    {
        $theme = new Theme();
        $form = $this->createForm(ThemeType::class, $theme);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($theme);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_admin_entity', ['entity' => 'themes']);
        }

        return $this->render('admin/add.html.twig', [
            'form' => $form->createView(),
            'entity' => 'themes',
        ]);
    }

    #[Route('admin/themes/edit/{id}', name: 'edit_themes')]
    public function edit(Request $request, Theme $theme): Response
    {
        $form = $this->createForm(ThemeType::class, $theme);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($theme);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_admin_entity', ['entity' => 'themes']);
        }

        return $this->render('admin/edit.html.twig', [
            'form' => $form->createView(),
            'entity' => 'themes',
            'data'=> $theme,
        ]);
    }

    #[Route('admin/themes/{id}/delete', name: 'delete_themes')]
    public function delete(int $id, Request $request): Response
    {
        $theme = $this->entityManager->getRepository(Theme::class)->find($id);

        if (!$theme) {
            throw $this->createNotFoundException('theme not found');
        }

        if ($request->isMethod('POST')) {

            $examples = $this->entityManager->getRepository(Example::class)->findBy(['theme' => $theme]);
            foreach ($examples as $example) {
                $this->entityManager->remove($example);
            }

            $this->entityManager->remove($theme);
            $this->entityManager->flush();

            return new JsonResponse(['success' => true, 'message' => 'Uživatel byl úspěšně smazán.']);
        }

        return $this->render('admin/delete.html.twig', [
            'data' => $theme,
            'entity' => 'themes',
        ]);
    }
}
