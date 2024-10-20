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
                    $factor1 = rand($minValue, $maxValue);
                    $factor2 = rand($minValue, $maxValue);
                    $equation = $this->formatNumber($factor1) . " × " . $this->formatNumber($factor2);
                    $product = $factor1 * $factor2;
                    break;

                case 'medium':
                    $factor1 = rand($minValue, $maxValue);
                    $factor2 = rand($minValue, $maxValue);
                    $factor3 = rand($minValue, $maxValue);
                    $partialProduct = $factor2 * $factor3;
                    $equation = $this->formatNumber($factor1) . " × (" . $this->formatNumber($factor2) . " × " . $this->formatNumber($factor3) . ")";
                    $product = $factor1 * $partialProduct;
                    break;

                case 'hard':
                    $factor1 = rand($minValue, $maxValue) * (rand(0, 1) ? 1 : -1);
                    $factor2 = rand($minValue, $maxValue) * (rand(0, 1) ? 1 : -1);
                    $factor3 = rand($minValue, $maxValue);
                    $equation = $this->formatNumber($factor1) . " × " . $this->formatNumber($factor2) . " × " . $this->formatNumber($factor3);
                    $product = $factor1 * $factor2 * $factor3;
                    break;

                default:
                    throw new \InvalidArgumentException("Neplatná náročnost: {$difficulty}");
            }

            $examples[] = [
                'equation' => $equation,
                'correctResult' => $product,
                'format' => ''
            ];
        }

        return $examples;
    }

    public function solve(string $equation, string $difficulty): array
    {
        $steps = [];

        switch ($difficulty) {
            case 'easy':
                preg_match('/(\-?\d+)\s*×\s*(\-?\d+)/', $equation, $matches);
                if (empty($matches)) {
                    throw new \InvalidArgumentException("Nesprávný formát rovnice.");
                }

                $factor1 = (int)$matches[1];
                $factor2 = (int)$matches[2];
                $product = $factor1 * $factor2;

                $steps[] = "Rovnice: " . $this->formatNumber($factor1) . " × " . $this->formatNumber($factor2);
                $steps[] = "Vynásobíme: {$factor1} × {$factor2} = {$product}";
                break;

            case 'medium':
                preg_match('/(\-?\d+)\s*×\s*\((\-?\d+)\s*×\s*(\-?\d+)\)/', $equation, $matches);
                if (empty($matches)) {
                    throw new \InvalidArgumentException("Nesprávný formát rovnice.");
                }

                $factor1 = (int)$matches[1];
                $factor2 = (int)$matches[2];
                $factor3 = (int)$matches[3];
                $partialProduct = $factor2 * $factor3;
                $product = $factor1 * $partialProduct;

                $steps[] = "Rovnice: " . $this->formatNumber($factor1) . " × (" . $this->formatNumber($factor2) . " × " . $this->formatNumber($factor3) . ")";
                $steps[] = "Vypočítáme vnitřní závorky: {$factor2} × {$factor3} = {$partialProduct}";
                $steps[] = "Poté vynásobíme: {$factor1} × {$partialProduct} = {$product}";
                break;

            case 'hard':
                preg_match('/(\-?\d+)\s*×\s*(\-?\d+)\s*×\s*(\-?\d+)/', $equation, $matches);
                if (empty($matches)) {
                    throw new \InvalidArgumentException("Nesprávný formát rovnice.");
                }

                $factor1 = (int)$matches[1];
                $factor2 = (int)$matches[2];
                $factor3 = (int)$matches[3];
                $product = $factor1 * $factor2 * $factor3;

                $steps[] = "Rovnice: " . $this->formatNumber($factor1) . " × " . $this->formatNumber($factor2) . " × " . $this->formatNumber($factor3);
                $steps[] = "Vynásobíme: {$factor1} × {$factor2} = " . ($factor1 * $factor2);
                $steps[] = "Pak vynásobíme výsledný součin a třetí faktor: " . ($factor1 * $factor2) . " × {$factor3} = {$product}";
                break;

            default:
                throw new \InvalidArgumentException("Nesprávná obtížnost: {$difficulty}");
        }

        return [
            'solution' => $product,
            'steps' => $steps
        ];
    }

    public function verify($input, $correctResult): bool
    {
        return abs($input - $correctResult) < 0.01;
    }

    private function formatNumber(int $number): string
    {
        return $number < 0 ? "({$number})" : (string)$number;
    }
}
