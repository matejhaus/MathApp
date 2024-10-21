<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('inflection', [$this, 'inflection']),
        ];
    }

    public function inflection(int $count, string $singular, string $few, string $many): string
    {
        if ($count === 1) {
            return $singular;
        } elseif ($count >= 2 && $count <= 4) {
            return $few;
        } else {
            return $many;
        }
    }
}
