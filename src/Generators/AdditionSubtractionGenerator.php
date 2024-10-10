<?php

namespace App\Generators;

use App\Generators\AbstractGenerator;

class AdditionSubtractionGenerator extends AbstractGenerator
{
    public function generate(int $minValue, int $maxValue, int $numberOfExamples, string $difficulty): array
    {
        $examples = [];

        for ($i = 0; $i < $numberOfExamples; $i++) {
            // Náhodně zvolíme počet čísel, které budeme sčítat nebo odčítat
            $numberCount = rand(2, 5);  // Počet čísel mezi 2 a 5

            $numbers = [];
            for ($j = 0; $j < $numberCount; $j++) {
                $numbers[] = rand($minValue, $maxValue) * ($difficulty === 'hard' && rand(0, 1) ? -1 : 1);
            }

            // Náhodně zvolíme, zda budou operace sčítání nebo odčítání
            $operations = [];
            for ($j = 0; $j < $numberCount - 1; $j++) {
                $operations[] = rand(0, 1) ? '+' : '-';
            }

            // Vytvoříme výraz a vypočítáme výsledek
            $expression = $this->createExpression($numbers, $operations);
            $result = $this->calculateResult($numbers, $operations);
            $steps = $this->generateSteps($numbers, $operations);

            $examples[] = [
                'equation' => $expression,
                'correctResult' => $result,
                'steps' => $steps
            ];
        }

        return $examples;
    }

    private function createExpression(array $numbers, array $operations): string
    {
        $expression = (string) $numbers[0];
        for ($i = 0; $i < count($operations); $i++) {
            $expression .= " {$operations[$i]} {$numbers[$i + 1]}";
        }
        return $expression;
    }

    private function calculateResult(array $numbers, array $operations): int
    {
        $result = $numbers[0];
        for ($i = 0; $i < count($operations); $i++) {
            if ($operations[$i] === '+') {
                $result += $numbers[$i + 1];
            } else {
                $result -= $numbers[$i + 1];
            }
        }
        return $result;
    }

    private function generateSteps(array $numbers, array $operations): array
    {
        $steps = [];
        $steps[] = "Máme příklad: " . $this->createExpression($numbers, $operations);

        $result = $numbers[0];
        for ($i = 0; $i < count($operations); $i++) {
            $operation = $operations[$i];
            $nextNumber = $numbers[$i + 1];
            if ($operation === '+') {
                $steps[] = "Sečteme {$result} + {$nextNumber} = " . ($result + $nextNumber);
                $result += $nextNumber;
            } else {
                $steps[] = "Odečteme {$result} - {$nextNumber} = " . ($result - $nextNumber);
                $result -= $nextNumber;
            }
        }

        $steps[] = "Výsledek je: {$result}";
        return $steps;
    }

    public function verify($input, $correctResult): bool
    {
        return abs($input - $correctResult) < 0.01; // kontrola s přesností na dvě desetinná místa
    }
}




