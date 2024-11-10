<?php

use PHPUnit\Framework\TestCase;
use App\Generators\EquationGenerator;

class EquationGeneratorTest extends TestCase
{
    private EquationGenerator $generator;

    protected function setUp(): void
    {
        $this->generator = new EquationGenerator();
    }

    public function testGenerateEasyEquation(): void
    {
        $examples = $this->generator->generate(1, 10, 1, 'easy');
        $this->assertCount(1, $examples);
        $this->assertArrayHasKey('equation', $examples[0]);
        $this->assertStringContainsString('x', $examples[0]['equation']);
    }

    public function testGenerateMediumEquation(): void
    {
        $examples = $this->generator->generate(1, 10, 1, 'medium');
        $this->assertCount(1, $examples);
        $this->assertArrayHasKey('equation', $examples[0]);
        $this->assertStringContainsString('*', $examples[0]['equation']);
    }

    public function testGenerateHardEquation(): void
    {
        $examples = $this->generator->generate(1, 10, 1, 'hard');
        $this->assertCount(1, $examples);
        $this->assertArrayHasKey('equation', $examples[0]);
        $this->assertStringContainsString('+', $examples[0]['equation']);
    }

    public function testSolveEasyEquation(): void
    {
        $equation = '2x + 4 = 10';
        $result = $this->generator->solve($equation, 'easy');

        $this->assertEquals(3, $result['solution']);
        $this->assertIsArray($result['steps']);
        $this->assertNotEmpty($result['steps']);
    }

    public function testSolveMediumEquation(): void
    {
        $equation = '3*(2 + 4x) = 24';
        $result = $this->generator->solve($equation, 'medium');

        $this->assertEquals(2, $result['solution']);
        $this->assertIsArray($result['steps']);
        $this->assertNotEmpty($result['steps']);
    }

    public function testSolveHardEquation(): void
    {
        $equation = '3x + 2*(5 + 4x) = 26';
        $result = $this->generator->solve($equation, 'hard');

        $this->assertEquals(1, $result['solution']);
        $this->assertIsArray($result['steps']);
        $this->assertNotEmpty($result['steps']);
    }

    public function testVerifyCorrectResult(): void
    {
        $this->assertTrue($this->generator->verify(3, 3));
        $this->assertTrue($this->generator->verify(3.0009, 3));
    }

    public function testVerifyIncorrectResult(): void
    {
        $this->assertFalse($this->generator->verify(4, 3));
        $this->assertFalse($this->generator->verify(2.98, 3));
    }

    public function testInvalidDifficultyGenerate(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->generator->generate(1, 10, 1, 'invalid');
    }

    public function testInvalidDifficultySolve(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->generator->solve('2x + 3 = 7', 'invalid');
    }
}
