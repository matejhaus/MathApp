<?php

namespace App\Generators;

use App\Generators\AbstractGenerator;

class DivisionGenerator extends AbstractGenerator
{
    public function generate(int $minValue, int $maxValue, int $numberOfExamples, string $difficulty): array
    {
        $examples = [];

        for ($i = 0; $i < $numberOfExamples; $i++) {
            switch ($difficulty) {
                case 'easy':
                    $divisor = rand($minValue, $maxValue);
                    $result = rand($minValue, $maxValue);
                    $dividend = $divisor * $result;
                    break;

                case 'medium':
                    $divisor = rand($minValue, $maxValue);
                    $result = rand($minValue, $maxValue);
                    $dividend = $divisor * $result + rand(0, $divisor - 1);
                    break;

                case 'hard':
                    $divisor = rand($minValue, $maxValue) * (rand(0, 1) ? 1 : -1);
                    $result = rand($minValue, $maxValue) * (rand(0, 1) ? 1 : -1);
                    $dividend = $divisor * $result + rand(0, abs($divisor) - 1);
                    break;

                default:
                    throw new \InvalidArgumentException("Neplatná náročnost: {$difficulty}");
            }

            $equation = "{$dividend} ÷ {$divisor}";

            $correctResult = intdiv($dividend, $divisor);
            $remainder = $dividend % $divisor;


            $steps = [];
            $steps[] = "Máme příklad: {$dividend} ÷ {$divisor}";
            $steps[] = "Zjistíme, kolikrát se {$divisor} vejde do {$dividend}.";
            $steps[] = "{$divisor} se vejde do {$dividend} celkem {$correctResult} krát.";

            if ($remainder !== 0) {
                $steps[] = "Po dělení zbyde zbytek {$remainder}, tedy máme výsledek: {$correctResult} zbytek {$remainder}.";
            } else {
                $steps[] = "Žádný zbytek nezůstane, takže výsledek je {$correctResult}.";
            }

            $examples[] = [
                'equation' => $equation,
                'correctResult' => $correctResult,
                'steps' => $steps
            ];
        }

        return $examples;
    }

    public function verify($input, $correctResult): bool
    {
        return abs($input - $correctResult) < 0.01;
    }
}
