<?php

namespace App\Generators;

use App\Generators\AbstractGenerator;

class InequalityGenerator extends AbstractGenerator
{
    public function generate(int $minValue, int $maxValue, int $numberOfExamples, string $difficulty): array
    {
        $examples = [];

        for ($i = 0; $i < $numberOfExamples; $i++) {
            // Pro každou iteraci generujeme nový příklad
            switch ($difficulty) {
                case 'easy':
                    // Jednoduché nerovnice s celými čísly
                    $a = rand($minValue, $maxValue);
                    $b = rand($minValue, $maxValue);
                    $x = rand($minValue, $maxValue);
                    $c = ($a * $x) + $b;
                    $inequalitySign = rand(0, 1) ? '>' : '<';
                    $equation = "{$a}x + {$b} {$inequalitySign} {$c}";
                    break;

                case 'medium':
                    // Střední nerovnice, možnost zlomků
                    $a = rand($minValue, $maxValue);
                    $b = rand($minValue, $maxValue);
                    $x = rand($minValue, $maxValue);
                    $c = ($a * $x) + $b;
                    $inequalitySign = rand(0, 1) ? '>' : '<';
                    $equation = "{$a}x + {$b} {$inequalitySign} {$c}";
                    break;

                case 'hard':
                    // Složitější nerovnice se zápornými čísly a možností zlomků
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

            // Výsledek řešení
            $correctResult = $x;

            // Kroky k řešení
            $steps = [];
            $steps[] = "Odečteme {$b} z obou stran: {$a}x {$inequalitySign} " . ($c - $b);
            $steps[] = "Vydělíme obě strany {$a}, abychom našli x: x {$inequalitySign} " . (($c - $b) / $a);

            // Uložíme příklad ve formátu podobném rovnicím
            $examples[] = [
                'equation' => $equation, // Změna z 'inequality' na 'equation'
                'correctResult' => $correctResult,
                'inequalitySign' => $inequalitySign, // Tento klíč je stále zde pro ověřování
                'steps' => $steps
            ];
        }

        return $examples;
    }

    public function verify($input, $correctResult): bool
    {
        // Načteme znak nerovnosti ze správného výsledku
        $inequalitySign = $correctResult['inequalitySign'];

        if ($inequalitySign === '>') {
            return $input > $correctResult['correctResult']; // Ověření pro nerovnici >
        } else {
            return $input < $correctResult['correctResult']; // Ověření pro nerovnici <
        }
    }
}
