<?php

namespace App\Generators;

use App\Generators\AbstractGenerator;

class TimeProblemGenerator extends AbstractGenerator
{
    public function generate(int $minValue, int $maxValue, int $numberOfExamples, string $difficulty): array
    {
        $examples = [];

        // Určení rozsahu čísel podle difficulty
        switch ($difficulty) {
            case 'easy':
                $maxDistance = 10;
                $maxSpeed = 5;
                break;
            case 'medium':
                $maxDistance = 50;
                $maxSpeed = 20;
                break;
            case 'hard':
                $maxDistance = 100;
                $maxSpeed = 50;
                break;
            default:
                throw new \InvalidArgumentException("Neplatná náročnost: {$difficulty}");
        }

        for ($i = 0; $i < $numberOfExamples; $i++) {
            $type = rand(1, 3);

            switch ($type) {
                case 1:
                    $distance = rand($minValue, $maxDistance);
                    $speed = rand(1, $maxSpeed);
                    $equation = "Jak dlouho trvá cesta, když jede auto rychlostí {$speed} km/h na vzdálenost {$distance} km?";
                    $format='v hodinách';
                    break;

                case 2:
                    $time = rand(1, 10);
                    $speed = rand(1, $maxSpeed);
                    $equation = "Jaká je vzdálenost, když auto jede rychlostí {$speed} km/h po dobu {$time} hodin?";
                    $format='v km';
                    break;

                case 3:
                    $distance = rand($minValue, $maxDistance);
                    $time = rand(1, 10);
                    $equation = "Jaká je rychlost, když auto ujede vzdálenost {$distance} km za {$time} hodin?";
                    $format = 'v km/h';
                    break;

                default:
                    throw new \InvalidArgumentException("Neplatný typ úlohy.");
            }

            $examples[] = [
                'equation' => $equation,
                'format' => $format,
            ];
        }

        return $examples;
    }

    public function solve(string $equation): array
    {
        $steps = [];
        $solution = null;

        if (preg_match('/Jak dlouho trvá cesta, když jede auto rychlostí (\d+) km\/h na vzdálenost (\d+) km/', $equation, $matches)) {
            $speed = (int)$matches[1];
            $distance = (int)$matches[2];
            $solution = $distance / $speed;
            $steps[] = "Vydělíme vzdálenost {$distance} km rychlostí {$speed} km/h: {$distance} / {$speed} = {$solution} hodin.";
        } elseif (preg_match('/Jaká je vzdálenost, když auto jede rychlostí (\d+) km\/h po dobu (\d+) hodin/', $equation, $matches)) {
            $speed = (int)$matches[1];
            $time = (int)$matches[2];
            $solution = $speed * $time; // vzdálenost = rychlost * čas
            $steps[] = "Vynásobíme rychlost {$speed} km/h a čas {$time} hodin: {$speed} * {$time} = {$solution} km.";
        } elseif (preg_match('/Jaká je rychlost, když auto ujede vzdálenost (\d+) km za (\d+) hodin/', $equation, $matches)) {
            $distance = (int)$matches[1];
            $time = (int)$matches[2];
            $solution = $distance / $time;
            $steps[] = "Vydělíme vzdálenost {$distance} km časem {$time} hodin: {$distance} / {$time} = {$solution} km/h.";
        } else {
            throw new \InvalidArgumentException("Neplatná úloha.");
        }

        return ['solution' => $solution, 'steps' => $steps];
    }

    public function verify($input, $correctResult): bool
    {
        return abs($correctResult - $input) < 0.01;
    }
}
