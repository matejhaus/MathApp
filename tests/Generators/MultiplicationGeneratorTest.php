<?php

use PHPUnit\Framework\TestCase;
use App\Generators\MultiplicationGenerator;

class MultiplicationGeneratorTest extends TestCase
{
    private MultiplicationGenerator $generator;

    protected function setUp(): void
    {
        $this->generator = new MultiplicationGenerator();
    }

    public function testGenerateEasyDifficulty()
    {
        $examples = $this->generator->generate(1, 10, 5, 'easy');

        $this->assertCount(5, $examples);
        foreach ($examples as $example) {
            $this->assertArrayHasKey('equation', $example);
            $this->assertArrayHasKey('correctResult', $example);

            preg_match('/(\-?\d+)\s*×\s*(\-?\d+)/', $example['equation'], $matches);
            $this->assertCount(3, $matches);

            $factor1 = (int)$matches[1];
            $factor2 = (int)$matches[2];
            $this->assertEquals($factor1 * $factor2, $example['correctResult']);
        }
    }

    public function testGenerateMediumDifficulty()
    {
        $examples = $this->generator->generate(1, 10, 5, 'medium');

        $this->assertCount(5, $examples);
        foreach ($examples as $example) {
            $this->assertArrayHasKey('equation', $example);
            $this->assertArrayHasKey('correctResult', $example);

            preg_match('/(\-?\d+)\s*×\s*\((\-?\d+)\s*×\s*(\-?\d+)\)/', $example['equation'], $matches);
            $this->assertCount(4, $matches);

            $factor1 = (int)$matches[1];
            $factor2 = (int)$matches[2];
            $factor3 = (int)$matches[3];
            $partialProduct = $factor2 * $factor3;
            $this->assertEquals($factor1 * $partialProduct, $example['correctResult']);
        }
    }

    public function testGenerateHardDifficulty()
    {
        $examples = $this->generator->generate(1, 10, 5, 'hard');

        $this->assertCount(5, $examples);
        foreach ($examples as $example) {
            $this->assertArrayHasKey('equation', $example);
            $this->assertArrayHasKey('correctResult', $example);

            preg_match('/(\-?\d+)\s*×\s*(\-?\d+)\s*×\s*(\-?\d+)/', $example['equation'], $matches);
            $this->assertCount(4, $matches);

            $factor1 = (int)$matches[1];
            $factor2 = (int)$matches[2];
            $factor3 = (int)$matches[3];
            $this->assertEquals($factor1 * $factor2 * $factor3, $example['correctResult']);
        }
    }

    public function testSolveEasyEquation()
    {
        $result = $this->generator->solve('3 × 4', 'easy');

        $this->assertEquals(12, $result['solution']);
        $this->assertEquals([
            "Rovnice: 3 × 4",
            "Vynásobíme: 3 × 4 = 12"
        ], $result['steps']);
    }

    public function testSolveMediumEquation()
    {
        $result = $this->generator->solve('2 × (3 × 4)', 'medium');

        $this->assertEquals(24, $result['solution']);
        $this->assertEquals([
            "Rovnice: 2 × (3 × 4)",
            "Vypočítáme vnitřní závorky: 3 × 4 = 12",
            "Poté vynásobíme: 2 × 12 = 24"
        ], $result['steps']);
    }

    public function testSolveHardEquation()
    {
        $result = $this->generator->solve('-2 × 3 × 4', 'hard');

        $this->assertEquals(-24, $result['solution']);
        $this->assertEquals([
            "Rovnice: (-2) × 3 × 4",
            "Vynásobíme: -2 × 3 = -6",
            "Pak vynásobíme výsledný součin a třetí faktor: -6 × 4 = -24"
        ], $result['steps']);
    }

    public function testVerify()
    {
        $this->assertTrue($this->generator->verify(24, 24));
        $this->assertFalse($this->generator->verify(20, 24));
    }
}
