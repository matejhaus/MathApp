<?php

namespace App\Generators;

use App\Generators\AbstractGenerator;

class InequalityGenerator extends AbstractGenerator
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
                    $inequalitySign = rand(0, 1) ? '>' : '<';
                    $equation = "{$a}x + {$b} {$inequalitySign} {$c}";
                    break;

                case 'medium':

                    $a = rand($minValue, $maxValue);
                    $b = rand($minValue, $maxValue);
                    $x = rand($minValue, $maxValue);
                    $c = ($a * $x) + $b;
                    $inequalitySign = rand(0, 1) ? '>' : '<';
                    $equation = "{$a}x + {$b} {$inequalitySign} {$c}";
                    break;

                case 'hard':
                    $a = rand($minValue, $maxValue) * (rand(0, 1) ? 1 : -1);
                    $b = rand($minValue, $maxValue) * (rand(0, 1) ? 1 : -1);
                    $x = rand($minValue, $maxValue);
                    $c = ($a * $x) + $b;
                    $inequalitySign = rand(0, 1) ? '>' : '<';
                    $equation = "{$a}x + {$b} {$inequalitySign} {$c}";
                    break;

                default:
                    throw new \InvalidArgumentException("Neplatná náročnost: {$difficulty}");
            }

            $correctResult = $x;

            $steps = [];
            $steps[] = "Odečteme {$b} z obou stran: {$a}x {$inequalitySign} " . ($c - $b);
            $steps[] = "Vydělíme obě strany {$a}, abychom našli x: x {$inequalitySign} " . (($c - $b) / $a);

            $examples[] = [
                'equation' => $equation,
                'correctResult' => $correctResult,
                'inequalitySign' => $inequalitySign,
                'steps' => $steps,
                'format' => ''
            ];
        }

        return $examples;
    }

    public function verify($input, $correctResult): bool
    {
        $inequalitySign = $correctResult['inequalitySign'];

        if ($inequalitySign === '>') {
            return $input > $correctResult['correctResult'];
        } else {
            return $input < $correctResult['correctResult'];
        }
    }
}
