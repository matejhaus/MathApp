<?php

namespace App\Generators;

use App\Generators\AbstractGenerator;

class EquationGenerator extends AbstractGenerator
{
    public function generate(int $minValue, int $maxValue, int $numberOfExamples, string $difficulty): array
    {
        $examples = [];

        for ($i = 0; $i < $numberOfExamples; $i++) {
            switch ($difficulty) {
                case 'easy':
                    $a = rand($minValue, $maxValue);
                    $b = rand($minValue, $maxValue);
                    $x = rand($minValue, $maxValue);
                    $c = ($a * $x) + $b;
                    $equation = "{$a}x + {$b} = {$c}";
                    break;

                case 'medium':
                    $a = rand($minValue, $maxValue);
                    $b = rand($minValue, $maxValue);
                    $x = rand($minValue, $maxValue);
                    $y = rand($minValue, $maxValue);
                    $c = $a * ($y + ($b * $x));
                    $equation = "{$a}*({$y} + {$b}x) = {$c}";
                    break;

                case 'hard':
                    $a = rand($minValue, $maxValue);
                    $b = rand($minValue, $maxValue);
                    $c = rand($minValue, $maxValue);
                    $d = rand($minValue, $maxValue);
                    $x = rand($minValue, $maxValue);
                    $e = ($a * $x) + $b * ($c + $d * $x);
                    $equation = "{$a}x + {$b}*({$c} + {$d}x) = {$e}";
                    break;

                default:
                    throw new \InvalidArgumentException("Neplatná náročnost: {$difficulty}");
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
            case 'easy':
                [$leftSide, $rightSide] = explode('=', $equation);
                $rightSide = trim($rightSide);

                preg_match('/([\-]?\d*)x\s*([+-]?\s*\d+)/', $leftSide, $matches);
                if (empty($matches)) {
                    throw new \InvalidArgumentException("Nesprávný formát rovnice.");
                }

                $a = $matches[1] === '-' ? -1 : (int) ($matches[1] ?: 1);
                $b = (int) str_replace(' ', '', $matches[2]);
                $c = (int) $rightSide;

                $steps[] = "Rovnice: {$a}x + {$b} = {$c}";
                $steps[] = "Odečteme konstantu z obou stran: {$a}x = " . ($c - $b);
                $x = ($c - $b) / $a;
                $steps[] = "Vydělíme obě strany koeficientem u 'x': x = " . $x;
                break;

            case 'medium':
                [$leftSide, $rightSide] = explode('=', $equation);
                $rightSide = trim($rightSide);

                preg_match('/([\-]?\d*)\*\((\d+)\s*([+-]\s*\d+)x\)/', $leftSide, $matches);
                if (empty($matches)) {
                    throw new \InvalidArgumentException("Nesprávný formát rovnice.");
                }

                $a = $matches[1] === '-' ? -1 : (int) ($matches[1] ?: 1);
                $y = (int) $matches[2];
                $b = (int) str_replace(' ', '', $matches[3]);
                $c = (int) $rightSide;

                $steps[] = "Rovnice: {$a}*({$y} + {$b}x) = {$c}";
                $steps[] = "Roznásobíme závorky: {$a}*{$y} + {$a}*{$b}x = {$c}";
                $steps[] = "Odečteme konstantu z obou stran: " . ($a * $b) . "x = " . ($c - ($a * $y));
                $x = ($c - ($a * $y)) / ($a * $b);
                $steps[] = "Vydělíme obě strany koeficientem u 'x': x = " . $x;
                break;

            case 'hard':
                [$leftSide, $rightSide] = explode('=', $equation);
                $rightSide = trim($rightSide);

                preg_match('/([\-]?\d*)x\s*([+-]\s*\d*)\*\((\d+)\s*([+-]\s*\d+)x\)/', $leftSide, $matches);
                if (empty($matches)) {
                    throw new \InvalidArgumentException("Nesprávný formát rovnice.");
                }

                $a = $matches[1] === '-' ? -1 : (int) ($matches[1] ?: 1);
                $b = (int) str_replace(' ', '', $matches[2]);
                $c = (int) $matches[3];
                $d = (int) str_replace(' ', '', $matches[4]);
                $e = (int) $rightSide;

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



