<?php

namespace App\Generators;

use App\Generators\AbstractGenerator;

class DivisionGenerator extends AbstractGenerator
{
    public function generate(int $minValue, int $maxValue, int $numberOfExamples, string $difficulty): array
    {
        $examples = [];

        for ($i = 0; $i < $numberOfExamples; $i++) {
            // Pro každou iteraci generujeme příklad na dělení
            switch ($difficulty) {
                case 'easy':
                    // Jednoduché dělení s celými čísly
                    $divisor = rand($minValue, $maxValue);
                    $result = rand($minValue, $maxValue);
                    $dividend = $divisor * $result;
                    break;

                case 'medium':
                    // Dělení s celými čísly a případně zlomky
                    $divisor = rand($minValue, $maxValue);
                    $result = rand($minValue, $maxValue);
                    $dividend = $divisor * $result + rand(0, $divisor - 1); // Možný zbytek
                    break;

                case 'hard':
                    // Dělení se zápornými čísly a zlomky
                    $divisor = rand($minValue, $maxValue) * (rand(0, 1) ? 1 : -1);
                    $result = rand($minValue, $maxValue) * (rand(0, 1) ? 1 : -1);
                    $dividend = $divisor * $result + rand(0, abs($divisor) - 1);
                    break;

                default:
                    throw new \InvalidArgumentException("Neplatná náročnost: {$difficulty}");
            }

            $equation = "{$dividend} ÷ {$divisor}";

            // Výsledek řešení
            $correctResult = intdiv($dividend, $divisor); // Celá část dělení
            $remainder = $dividend % $divisor; // Zbytek dělení

            // Kroky k řešení
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
        return abs($input - $correctResult) < 0.01; // kontrola s přesností na dvě desetinná místa
    }
}
