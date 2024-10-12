<?php

namespace App\Generators;

use App\Generators\AbstractGenerator;

class AdditionSubtractionGenerator extends AbstractGenerator
{
    public function generate(int $minValue, int $maxValue, int $numberOfExamples, string $difficulty): array
    {
        $examples = [];

        for ($i = 0; $i < $numberOfExamples; $i++) {
            $numberCount = rand(2, 5);

            $numbers = [];
            for ($j = 0; $j < $numberCount; $j++) {
                $numbers[] = rand($minValue, $maxValue) * ($difficulty === 'hard' && rand(0, 1) ? -1 : 1);
            }

            $operations = [];
            for ($j = 0; $j < $numberCount - 1; $j++) {
                $operations[] = rand(0, 1) ? '+' : '-';
            }

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

    public function solve(string $equation, string $difficulty): array
    {
        $parsedData = $this->parseEquation($equation);
        $numbers = $parsedData['numbers'];
        $operations = $parsedData['operations'];

        $result = $this->calculateResult($numbers, $operations);
        $steps = $this->generateSteps($numbers, $operations);

        return [
            'solution' => $result,
            'steps' => $steps
        ];
    }

    public function verify($input, $correctResult): bool
    {
        return abs($input - $correctResult) < 0.01;
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

    private function parseEquation(string $equation): array
    {
        preg_match_all('/-?\d+/', $equation, $numbers);
        preg_match_all('/[+-]/', $equation, $operations);

        return [
            'numbers' => array_map('intval', $numbers[0]),
            'operations' => $operations[0]
        ];
    }
}
