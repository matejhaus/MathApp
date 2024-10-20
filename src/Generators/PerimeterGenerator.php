<?php

namespace App\Generators;

use App\Generators\AbstractGenerator;

class PerimeterGenerator extends AbstractGenerator
{
    public function generate(int $minValue, int $maxValue, int $numberOfExamples, string $difficulty): array
    {
        $examples = [];

        for ($i = 0; $i < $numberOfExamples; $i++) {
            switch ($difficulty) {
                case 'square':
                    $side = rand($minValue, $maxValue);
                    $equation = "Obvod čtverce se stranou {$side} cm";
                    break;

                case 'rectangle':
                    $length = rand($minValue, $maxValue);
                    $width = rand($minValue, $maxValue);
                    $equation = "Obvod obdélníku s délkou {$length} cm a šířkou {$width} cm";
                    break;

                case 'triangle':
                    $side1 = rand($minValue, $maxValue);
                    $side2 = rand($minValue, $maxValue);
                    $side3 = rand($minValue, $maxValue);
                    $equation = "Obvod trojúhelníku se stranami {$side1} cm, {$side2} cm, a {$side3} cm";
                    break;

                case 'circle':
                    $radius = rand($minValue, $maxValue);
                    $equation = "Obvod kruhu s poloměrem {$radius} cm";
                    break;

                default:
                    throw new \InvalidArgumentException("Neplatný tvar: {$difficulty}");
            }

            $examples[] = [
                'equation' => $equation,
                'format' => ''
            ];
        }

        return $examples;
    }

    public function solve(string $equation, string $difficulty): array
    {
        $steps = [];

        switch ($difficulty) {
            case 'square':
                preg_match('/stranou\s*(\d+)/', $equation, $matches);
                $side = (int)$matches[1];
                $perimeter = 4 * $side;

                $steps[] = "Příklad: Obvod čtverce se stranou {$side} cm: 4 × {$side}";
                $steps[] = "Vypočítáme obvod: 4 × {$side} = {$perimeter}";
                break;

            case 'rectangle':
                preg_match('/Obvod\s*obdélníku\s*s\s*délkou\s*(\d+)\s*cm\s*a\s*šířkou\s*(\d+)\s*cm/', $equation, $matches);
                $length = (int)$matches[1];
                $width = (int)$matches[2];
                $perimeter = 2 * ($length + $width);

                $steps[] = "Příklad: Obvod obdélníku s délkou {$length} cm a šířkou {$width} cm: 2 × ({$length} + {$width})";
                $steps[] = "Vypočítáme součet stran: {$length} + {$width} = " . ($length + $width);
                $steps[] = "Vynásobíme 2: 2 × " . ($length + $width) . " = {$perimeter} cm";
                break;

            case 'triangle':
                preg_match('/stranami\s*(\d+)\s*cm,\s*(\d+)\s*cm,\sa\s*(\d+)\s*cm/', $equation, $matches);
                $side1 = (int)$matches[1];
                $side2 = (int)$matches[2];
                $side3 = (int)$matches[3];
                $perimeter = $side1 + $side2 + $side3;

                $steps[] = "Příklad: Obvod trojúhelníku se stranami {$side1} cm, {$side2} cm, a {$side3} cm";
                $steps[] = "Vypočítáme součet stran: {$side1} cm + {$side2} cm + {$side3} cm = {$perimeter} cm";
                break;

            case 'circle':
                preg_match('/poloměrem\s*(\d+)/', $equation, $matches);
                $radius = (int)$matches[1];
                $perimeter = 2 * pi() * $radius;

                $steps[] = "Příklad: Obvod kruhu s poloměrem {$radius} cm: 2 × π × {$radius}";
                $steps[] = "Vypočítáme obvod: 2 × π × {$radius} ≈ " . round($perimeter, 2).' cm';
                break;

            default:
                throw new \InvalidArgumentException("Neplatný tvar: {$difficulty}");
        }

        return [
            'solution' => round($perimeter, 2).' cm',
            'steps' => $steps
        ];
    }

    public function verify($input, $correctResult): bool
    {
        return abs($input - $correctResult) < 0.01;
    }
}
