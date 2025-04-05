<?php

namespace App\Utils;

use App\Entity\Example;
use App\Entity\Theme;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ExampleCsvImporter
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function importCsvFile(UploadedFile $file): void
    {
        if ($file->getClientOriginalExtension() !== 'csv') {
            throw new FileException('Soubor musí být ve formátu CSV');
        }

        $csvContent = file_get_contents($file->getPathname());
        $rows = explode("\n", $csvContent);
        array_shift($rows);

        foreach ($rows as $row) {
            if (!empty($row)) {
                $data = str_getcsv($row, ";");

                if (count($data) === 3) {
                    $example = new Example();

                    $themeId = (int)$data[0];
                    $theme = $this->entityManager->getRepository(Theme::class)->find($themeId);

                    if ($theme) {
                        $example->setTheme($theme);
                        $example->setQuestion($data[1]);
                        $example->setResult($data[2]);

                        $this->entityManager->persist($example);
                    }
                }
            }
        }

        $this->entityManager->flush();
    }
}
