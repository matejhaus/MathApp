<?php

namespace App\TwigExtension;

use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class GlobalVariablesExtension extends AbstractExtension implements GlobalsInterface
{
    public function getGlobals(): array
    {
        return [
            'ahoj' => 'ahoj',
        ];
    }
}