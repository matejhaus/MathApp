<?php
namespace App\Tests\Generators;

use App\Generators\DivisionGenerator;
use PHPUnit\Framework\TestCase;

class DivisionGeneratorTest extends TestCase
{
    public function testGenerateEasyExamples()
    {
        $generator = new DivisionGenerator();
        $examples = $generator->generate(1, 10, 5, 'easy');

        $this->assertCount(5, $examples);

        foreach ($examples as $example) {
            $this->assertArrayHasKey('equation', $example);
            [$dividend, $divisor] = sscanf($example['equation'], '%d ÷ %d');
            $this->assertGreaterThanOrEqual(1, $divisor);
            $this->assertGreaterThanOrEqual(1, $dividend);
            $this->assertEquals(0, $dividend % $divisor);
        }
    }

    public function testGenerateMediumExamples()
    {
        $generator = new DivisionGenerator();
        $examples = $generator->generate(1, 10, 5, 'medium');

        $this->assertCount(5, $examples);

        foreach ($examples as $example) {
            $this->assertArrayHasKey('equation', $example);
            [$dividend, $divisor] = sscanf($example['equation'], '%d ÷ %d');
            $this->assertGreaterThanOrEqual(1, $divisor);
            $this->assertGreaterThanOrEqual(1, $dividend);
            $this->assertGreaterThanOrEqual(1, $dividend % $divisor);
        }
    }

    public function testGenerateHardExamples()
    {
        $generator = new DivisionGenerator();
        $examples = $generator->generate(1, 10, 5, 'hard');

        $this->assertCount(5, $examples);

        foreach ($examples as $example) {
            $this->assertArrayHasKey('equation', $example);
            [$dividend, $divisor] = sscanf($example['equation'], '%d ÷ %d');
            $this->assertNotEquals(0, $divisor);
        }
    }

    public function testSolveEasyEquation()
    {
        $generator = new DivisionGenerator();
        $equation = '10 ÷ 2';
        $solution = $generator->solve($equation, 'easy');

        $this->assertEquals(5, $solution['solution']);
        $this->assertIsArray($solution['steps']);
        $this->assertNotEmpty($solution['steps']);
    }

    public function testSolveWithRemainder()
    {
        $generator = new DivisionGenerator();
        $equation = '10 ÷ 3';
        $solution = $generator->solve($equation, 'medium');

        $this->assertEquals(3, $solution['solution']);
        $this->assertIsArray($solution['steps']);
        $this->assertNotEmpty($solution['steps']);
    }

    public function testVerifyCorrectResult()
    {
        $generator = new DivisionGenerator();
        $this->assertTrue($generator->verify(5, 5));
    }

    public function testVerifyIncorrectResult()
    {
        $generator = new DivisionGenerator();
        $this->assertFalse($generator->verify(5, 4.98));
    }

    public function testInvalidDifficulty()
    {
        $this->expectException(\InvalidArgumentException::class);
        $generator = new DivisionGenerator();
        $generator->generate(1, 10, 5, 'invalid');
    }
}
