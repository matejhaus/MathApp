<?php

namespace App\Generators;

use App\Generators\AbstractGenerator;

class MultiplicationGenerator extends AbstractGenerator
{
    public function generate(int $minValue, int $maxValue, int $numberOfExamples, string $difficulty): array
    {
        $examples = [];

        for ($i = 0; $i < $numberOfExamples; $i++) {
            switch ($difficulty) {
                case 'easy':
                    // Jednoduché násobení
                    $factor1 = rand($minValue, $maxValue);
                    $factor2 = rand($minValue, $maxValue);
                    $product = $factor1 * $factor2;
                    $equation = "{$factor1} × {$factor2}";
                    break;

                case 'medium':
                    // Střední úroveň s patternem a*(b*c)
                    $factor1 = rand($minValue, $maxValue);
                    $factor2 = rand($minValue, $maxValue);
                    $factor3 = rand($minValue, $maxValue);
                    $partialProduct = $factor2 * $factor3;
                    $product = $factor1 * $partialProduct;
                    $equation = "{$factor1} × ({$factor2} × {$factor3})";
                    break;

                case 'hard':
                    // Těžká úroveň se zápornými čísly
                    $factor1 = rand($minValue, $maxValue) * (rand(0, 1) ? 1 : -1);
                    $factor2 = rand($minValue, $maxValue) * (rand(0, 1) ? 1 : -1);
                    $product = $factor1 * $factor2;
                    $equation = "{$factor1} × {$factor2}";
                    break;

                default:
                    throw new \InvalidArgumentException("Neplatná náročnost: {$difficulty}");
            }

            $steps = [];
            if ($difficulty === 'medium') {
                $steps[] = "Vypočítáme vnitřní závorky: {$factor2} × {$factor3} = {$partialProduct}.";
                $steps[] = "Poté vynásobíme {$factor1} × {$partialProduct}, což nám dává výsledek {$product}.";
            } else {
                $steps[] = "Vynásobíme {$factor1} a {$factor2}, což nám dává výsledek {$product}.";
            }

            $examples[] = [
                'equation' => $equation,
                'correctResult' => $product,
                'steps' => $steps
            ];
        }

        return $examples;
    }

    public function verify($input, $correctResult): bool
    {
        return abs($input - $correctResult) < 0.01; // kontrola s přesností na dvě desetinná místa
    }
}
