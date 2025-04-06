<?php

namespace App\Controller;

use App\Entity\Block;
use App\Entity\Example;
use App\Entity\TestSettings;
use App\Entity\Theme;
use App\Form\BlockType;
use App\Form\ThemeSettingsType;
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
        $blocks = $theme->getBlocks();
        $count = $theme->getExamplesCount();

        if (!$theme) {
            throw $this->createNotFoundException('Theme not found');
        }

        return $this->render('theme/theme.html.twig', [
            'theme' => $theme,
            'examplesCount' => $count,
            'blocks' => $blocks
        ]);
    }

    #[Route('admin/theme/add', name: 'add_themes')]
    public function add(Request $request): Response
    {
        $theme = new Theme();

        $testSettings = new TestSettings();
        $testSettings->setTimeLimitInMinutes(30);
        $testSettings->setNumberOfQuestions(10);
        $testSettings->setRandomOrder(true);
        $testSettings->setShowCorrectAnswersAfter(true);
        $testSettings->setIsPracticeMode(false);
        $testSettings->setGrade1Percentage(90);
        $testSettings->setGrade2Percentage(80);
        $testSettings->setGrade3Percentage(70);
        $testSettings->setGrade4Percentage(60);
        $testSettings->setGrade5Percentage(50);

        $theme->setTestSettings($testSettings);
        $form = $this->createForm(ThemeType::class, $theme);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($testSettings);
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

    #[Route('admin/themes/settings/edit/{id}', name: 'edit_themes_settings')]
    public function editSettings(Request $request, TestSettings $testSettings): Response
    {
        $form = $this->createForm(ThemeSettingsType::class, $testSettings);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($testSettings);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_admin_entity', ['entity' => 'themes']);
        }

        return $this->render('admin/edit.html.twig', [
            'form' => $form->createView(),
            'entity' => 'themes_settings',
            'data' => $testSettings,
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

            $testSettings = $this->entityManager->getRepository(TestSettings::class)->findOneBy(['theme' => $theme]);
            if ($testSettings) {
                $this->entityManager->remove($testSettings);
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
    #[Route('/admin/blocks/edit/{id}', name: 'edit_blocks')]
    public function editBlocks(Request $request, Theme $theme): Response
    {
        // Načíst všechny bloky pro dané téma
        $blocks = $this->entityManager->getRepository(Block::class)->findBy(['theme' => $theme]);

        $forms = [];

        // Vytvořit formuláře pro každý blok
        foreach ($blocks as $block) {
            $form = $this->createForm(BlockType::class, $block);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->entityManager->flush();
                $this->addFlash('success', 'Bloky byly úspěšně upraveny.');
            }

            $forms[] = $form->createView();
        }

        return $this->render('admin/edit_blocks.html.twig', [
            'theme' => $theme,
            'forms' => $forms,
        ]);
    }

    #[Route('admin/block/add', name: 'add_blocks')]
    public function addBlock(Request $request): Response
    {
        $block=new Block();
        $form = $this->createForm(BlockType::class, $block);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($block);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_admin_entity', ['entity' => 'themes']);
        }

        return $this->render('admin/add.html.twig', [
            'form' => $form->createView(),
            'entity' => 'blocks',
        ]);
    }
}
