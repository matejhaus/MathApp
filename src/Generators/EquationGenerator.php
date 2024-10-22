<?php

namespace App\Generators;

use App\Generators\AbstractGenerator;

class EquationGenerator
{
    // Helper function to format numbers, removing unnecessary parentheses and fixing +/- issues
    private function formatEquation(string $equation): string
    {
        // Nahrazení "+ -" a "- -" na korektní zápis
        $equation = str_replace(['+ -', '- -'], ['- ', '+ '], $equation);

        return $equation;
    }

    // Helper function to format numbers without unnecessary parentheses
    private function formatNumber(int $number): string
    {
        return $number < 0 ? (string) $number : (string) $number;
    }

    // Helper function to generate random non-zero numbers
    private function generateNonZeroRandom(int $minValue, int $maxValue): int
    {
        $number = 0;
        while ($number === 0) {
            $number = rand($minValue, $maxValue);
        }
        return $number;
    }

    public function generate(int $minValue, int $maxValue, int $numberOfExamples, string $difficulty): array
    {
        $examples = [];

        for ($i = 0; $i < $numberOfExamples; $i++) {
            switch ($difficulty) {
                case 'easy':
                    $a = $this->generateNonZeroRandom(-$minValue, $maxValue);
                    $b = rand(-$minValue, $maxValue);
                    $x = $this->generateNonZeroRandom(-$minValue, $maxValue);
                    $c = ($a * $x) + $b;

                    $equation = "{$this->formatNumber($a)}x + {$this->formatNumber($b)} = {$this->formatNumber($c)}";
                    break;

                case 'medium':
                    $a = $this->generateNonZeroRandom(-$minValue, $maxValue);
                    $b = $this->generateNonZeroRandom(-$minValue, $maxValue);
                    $x = $this->generateNonZeroRandom(-$minValue, $maxValue);
                    $y = rand(-$minValue, $maxValue);
                    $c = $a * ($y + ($b * $x));

                    $equation = "{$this->formatNumber($a)}*({$this->formatNumber($y)} + {$this->formatNumber($b)}x) = {$this->formatNumber($c)}";
                    break;

                case 'hard':
                    $a = $this->generateNonZeroRandom(-$minValue, $maxValue);
                    $b = $this->generateNonZeroRandom(-$minValue, $maxValue);
                    $c = rand(-$minValue, $maxValue);
                    $d = $this->generateNonZeroRandom(-$minValue, $maxValue);
                    $x = $this->generateNonZeroRandom(-$minValue, $maxValue);
                    $e = ($a * $x) + $b * ($c + $d * $x);

                    $equation = "{$this->formatNumber($a)}x + {$this->formatNumber($b)}*({$this->formatNumber($c)} + {$this->formatNumber($d)}x) = {$this->formatNumber($e)}";
                    break;

                default:
                    throw new \InvalidArgumentException("Neplatná náročnost: {$difficulty}");
            }

            $equation = $this->formatEquation($equation);

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
            case 'easy':
                [$leftSide, $rightSide] = explode('=', $equation);
                $rightSide = trim($rightSide);

                preg_match('/([\-]?\d*)x\s*([+-]?\s*\(?\-?\d+\)?)/', $leftSide, $matches);
                if (empty($matches)) {
                    throw new \InvalidArgumentException("Nesprávný formát rovnice.");
                }

                $a = $matches[1] === '-' ? -1 : (int) ($matches[1] ?: 1);
                $b = (int) str_replace([' ', '(', ')'], '', $matches[2]);
                $c = (int) str_replace([' ', '(', ')'], '', $rightSide);

                $steps[] = "Rovnice: {$a}x + {$b} = {$c}";
                $steps[] = "Odečteme konstantu z obou stran: {$a}x = " . ($c - $b);
                $x = ($c - $b) / $a;
                $steps[] = "Vydělíme obě strany koeficientem u 'x': x = " . $x;
                break;

            case 'medium':
                [$leftSide, $rightSide] = explode('=', $equation);
                $rightSide = trim($rightSide);

                preg_match('/([\-]?\d*)\*\((\-?\d+)\s*([+-]\s*\(?\-?\d+\)?x)\)/', $leftSide, $matches);
                if (empty($matches)) {
                    throw new \InvalidArgumentException("Nesprávný formát rovnice.");
                }

                $a = $matches[1] === '-' ? -1 : (int) ($matches[1] ?: 1);
                $y = (int) str_replace(['(', ')'], '', $matches[2]);
                $b = (int) str_replace([' ', '(', ')'], '', $matches[3]);
                $c = (int) str_replace([' ', '(', ')'], '', $rightSide);

                $steps[] = "Rovnice: {$a}*({$y} + {$b}x) = {$c}";
                $steps[] = "Roznásobíme závorky: {$a}*{$y} + {$a}*{$b}x = {$c}";
                $steps[] = "Odečteme konstantu z obou stran: " . ($a * $b) . "x = " . ($c - ($a * $y));
                $x = ($c - ($a * $y)) / ($a * $b);
                $steps[] = "Vydělíme obě strany koeficientem u 'x': x = " . $x;
                break;

            case 'hard':
                [$leftSide, $rightSide] = explode('=', $equation);
                $rightSide = trim($rightSide);

                preg_match('/([\-]?\d*)x\s*([+-]\s*\(?\-?\d+\)?)\*\((\-?\d+)\s*([+-]\s*\(?\-?\d+\)?x)\)/', $leftSide, $matches);
                if (empty($matches)) {
                    throw new \InvalidArgumentException("Nesprávný formát rovnice.");
                }

                $a = $matches[1] === '-' ? -1 : (int) ($matches[1] ?: 1);
                $b = (int) str_replace([' ', '(', ')'], '', $matches[2]);
                $c = (int) str_replace(['(', ')'], '', $matches[3]);
                $d = (int) str_replace([' ', '(', ')'], '', $matches[4]);
                $e = (int) str_replace([' ', '(', ')'], '', $rightSide);

                $steps[] = "Rovnice: {$a}x + {$b}*({$c} + {$d}x) = {$e}";
                $steps[] = "Roznásobíme závorku: {$a}x + {$b}*{$c} + {$b}*{$d}x = {$e}";
                $steps[] = "Spojíme koeficienty u 'x': (" . ($a + $b * $d) . ")x + " . ($b * $c) . " = {$e}";
                $steps[] = "Odečteme konstantu z obou stran: " . ($a + $b * $d) . "x = " . ($e - ($b * $c));
                $x = ($e - ($b * $c)) / ($a + $b * $d);
                $steps[] = "Vydělíme obě strany koeficientem u 'x': x = " . $x;
                break;

            default:
                throw new \InvalidArgumentException("Nesprávná obtížnost: {$difficulty}");
        }

        return [
            'solution' => $x,
            'steps' => $steps
        ];
    }

    public function verify($input, $correctResult): bool
    {
        return abs($input - $correctResult) < 0.01;
    }
}
