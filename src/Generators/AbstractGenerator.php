<?php

namespace App\Generators;

abstract class AbstractGenerator
{
    // Metoda pro generování příkladu (bude implementována v děděných třídách)
    abstract public function generate(int $minValue, int $maxValue, int $numberOfExamples, string $difficulty): array;

    // Metoda pro ověření výsledku
    abstract public function verify($input, $correctResult): bool;
}