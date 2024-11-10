<?php

namespace App\Tests\Generators;

use App\Generators\TimeProblemGenerator;
use PHPUnit\Framework\TestCase;

class TimeProblemGeneratorTest extends TestCase
{
    public function testGenerateEasyDifficulty()
    {
        $generator = new TimeProblemGenerator();
        $examples = $generator->generate(1, 10, 5, 'easy');

        $this->assertCount(5, $examples);
        foreach ($examples as $example) {
            $this->assertIsArray($example);
            $this->assertArrayHasKey('equation', $example);
            $this->assertArrayHasKey('format', $example);
        }
    }

    public function testSolveTravelTime()
    {
        $generator = new TimeProblemGenerator();
        $equation = "Jak dlouho trvá cesta, když jede auto rychlostí 60 km/h na vzdálenost 120 km?";
        $result = $generator->solve($equation);

        $this->assertEquals(2, $result['solution']);
        $this->assertStringContainsString("Vydělíme vzdálenost 120 km rychlostí 60 km/h", $result['steps'][0]);
    }

    public function testSolveDistance()
    {
        $generator = new TimeProblemGenerator();
        $equation = "Jaká je vzdálenost, když auto jede rychlostí 40 km/h po dobu 3 hodin?";
        $result = $generator->solve($equation);

        $this->assertEquals(120, $result['solution']);
        $this->assertStringContainsString("Vynásobíme rychlost 40 km/h a čas 3 hodin", $result['steps'][0]);
    }

    public function testSolveSpeed()
    {
        $generator = new TimeProblemGenerator();
        $equation = "Jaká je rychlost, když auto ujede vzdálenost 150 km za 3 hodin?";
        $result = $generator->solve($equation);

        $this->assertEquals(50, $result['solution']);
        $this->assertStringContainsString("Vydělíme vzdálenost 150 km časem 3 hodin", $result['steps'][0]);
    }

    public function testVerifyCorrectAnswer()
    {
        $generator = new TimeProblemGenerator();
        $correctResult = 50.0;
        $this->assertTrue($generator->verify(50.0, $correctResult));
        $this->assertTrue($generator->verify(50.01, $correctResult));
    }

    public function testVerifyIncorrectAnswer()
    {
        $generator = new TimeProblemGenerator();
        $correctResult = 50.0;
        $this->assertFalse($generator->verify(49.98, $correctResult));
    }
}
