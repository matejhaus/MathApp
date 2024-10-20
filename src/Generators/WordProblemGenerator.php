<?php

namespace App\Generators;

use App\Generators\AbstractGenerator;

class WordProblemGenerator extends AbstractGenerator
{
    public function generate(int $minValue, int $maxValue, int $numberOfExamples, string $difficulty): array
    {
        $examples = [];

        // Nastavíme rozsah podle obtížnosti
        switch ($difficulty) {
            case 'easy':
                $rangeMin = 1;
                $rangeMax = 10; // Menší čísla
                break;
            case 'medium':
                $rangeMin = 11;
                $rangeMax = 50; // Střední čísla
                break;
            case 'hard':
                $rangeMin = 51;
                $rangeMax = 100; // Větší čísla
                break;
            default:
                throw new \InvalidArgumentException("Neplatná obtížnost: {$difficulty}");
        }

        for ($i = 0; $i < $numberOfExamples; $i++) {
            // Náhodně vybereme typ úlohy
            $problemType = rand(1, 4); // 1: sčítání, 2: odčítání, 3: násobení, 4: dělení

            switch ($problemType) {
                case 1: // Sčítání
                    $item1 = rand($rangeMin, $rangeMax);
                    $item2 = rand($rangeMin, $rangeMax);
                    $equation = "Na farmě bylo {$item1} jablek. Poté farmář přidal {$item2} jablek. Kolik jablek má nyní?";
                    break;

                case 2: // Odčítání
                    $item1 = rand($rangeMin, $rangeMax);
                    $item2 = rand(1, $item1); // zajistit, že nebudeme mít záporný výsledek
                    $equation = "V krabici bylo {$item1} bonbónů. Pokud si vezmeme {$item2} bonbónů, kolik bonbónů zůstane?";
                    break;

                case 3: // Násobení
                    $item1 = rand(1, 10); // Malá čísla pro smysluplné násobení
                    $item2 = rand(1, 10);
                    $equation = "Každý balíček obsahuje {$item1} sušenek. Kolik sušenek je v {$item2} balíčkách?";
                    break;

                case 4: // Dělení
                    $item1 = rand($rangeMin, $rangeMax) * rand(1, 5); // zajistit, že výsledek bude celé číslo
                    $item2 = rand(1, 5);
                    $equation = "Máme {$item1} jablek, která chceme rozdělit mezi {$item2} přátel. Kolik jablek dostane každý přítel?";
                    break;

                default:
                    throw new \InvalidArgumentException("Neplatný typ úlohy.");
            }

            $examples[] = [
                'equation' => $equation,
                'format' => ''
            ];
        }

        return $examples;
    }

    public function verify($input, $correctResult): bool
    {
        return abs($input - $correctResult) < 0.01;
    }

    public function solve(string $equation): array
    {
        $solution = 0;
        $steps = [];

        if (preg_match('/Na farmě bylo (\d+) jablek. Poté farmář přidal (\d+) jablek/', $equation, $matches)) {
            $jablek1 = (int)$matches[1];
            $jablek2 = (int)$matches[2];
            $solution = $jablek1 + $jablek2;
            $steps[] = "Vypočítáme: {$jablek1} + {$jablek2} = {$solution}";
        }

        elseif (preg_match('/V krabici bylo (\d+) bonbónů. Pokud si vezmeme (\d+) bonbónů/', $equation, $matches)) {
            $bonbony1 = (int)$matches[1];
            $bonbony2 = (int)$matches[2];
            $solution = $bonbony1 - $bonbony2;
            $steps[] = "Vypočítáme: {$bonbony1} - {$bonbony2} = {$solution}";
        }

        elseif (preg_match('/Každý balíček obsahuje (\d+) sušenek. Kolik sušenek je v (\d+) balíčkách/', $equation, $matches)) {
            $susenkY1 = (int)$matches[1];
            $balicky = (int)$matches[2];
            $solution = $susenkY1 * $balicky;
            $steps[] = "Vypočítáme: {$susenkY1} * {$balicky} = {$solution}";
        }

        elseif (preg_match('/Máme (\d+) jablek, která chceme rozdělit mezi (\d+) přátel/', $equation, $matches)) {
            $jablek = (int)$matches[1];
            $pratel = (int)$matches[2];
            $solution = $jablek / $pratel;
            $steps[] = "Vypočítáme: {$jablek} / {$pratel} = {$solution}";
        }

        else {
            throw new \InvalidArgumentException("Neplatná úloha.");
        }

        return [
            'solution' => $solution,
            'steps' => $steps
        ];
    }

}
