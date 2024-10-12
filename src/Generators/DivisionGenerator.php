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
                    $equation = "{$dividend} ÷ {$divisor}";
                    break;

                case 'medium':
                    $divisor = rand($minValue, $maxValue);
                    $result = rand($minValue, $maxValue);
                    $remainder = rand(1, $divisor - 1);
                    $dividend = $divisor * $result + $remainder;
                    $equation = "{$dividend} ÷ {$divisor}";
                    break;

                case 'hard':
                    $divisor = rand($minValue, $maxValue) * (rand(0, 1) ? 1 : -1);
                    $result = rand($minValue, $maxValue) * (rand(0, 1) ? 1 : -1);
                    $dividend = $divisor * $result + rand(0, abs($divisor) - 1);
                    $equation = "{$dividend} ÷ {$divisor}";
                    break;

                default:
                    throw new \InvalidArgumentException("Neplatná náročnost: {$difficulty}");
            }

            $examples[] = [
                'equation' => $equation,
            ];
        }

        return $examples;
    }

    public function solve(string $equation, string $difficulty): array
    {
        [$dividend, $divisor] = sscanf($equation, '%d ÷ %d');
        $correctResult = intdiv($dividend, $divisor);
        $remainder = $dividend % $divisor;

        $steps = $this->generateSteps($dividend, $divisor, $correctResult, $remainder);

        return [
            'solution' => $correctResult,
            'steps' => $steps,
        ];
    }

    private function generateSteps(int $dividend, int $divisor, int $correctResult, int $remainder): array
    {
        $steps = [];
        $steps[] = "Máme příklad: {$dividend} ÷ {$divisor}";
        $steps[] = "Zjistíme, kolikrát se {$divisor} vejde do {$dividend}.";
        $steps[] = "{$divisor} se vejde do {$dividend} celkem {$correctResult} krát.";

        if ($remainder !== 0) {
            $steps[] = "Po dělení zbyde zbytek {$remainder}, tedy máme výsledek: {$correctResult} zbytek {$remainder}.";
        } else {
            $steps[] = "Žádný zbytek nezůstane, takže výsledek je {$correctResult}.";
        }

        return $steps;
    }

    public function verify($input, $correctResult): bool
    {
        return abs($input - $correctResult) < 0.01;
    }
}
