<?php

namespace App\Tests\Generators;

use PHPUnit\Framework\TestCase;
use App\Generators\PerimeterGenerator;

class PerimeterGeneratorTest extends TestCase
{
    private PerimeterGenerator $generator;

    protected function setUp(): void
    {
        $this->generator = new PerimeterGenerator();
    }

    public function testGenerateSquare(): void
    {
        $examples = $this->generator->generate(1, 10, 3, 'square');
        $this->assertCount(3, $examples);

        foreach ($examples as $example) {
            $this->assertStringContainsString('Obvod čtverce se stranou', $example['equation']);
        }
    }

    public function testGenerateRectangle(): void
    {
        $examples = $this->generator->generate(1, 10, 3, 'rectangle');
        $this->assertCount(3, $examples);

        foreach ($examples as $example) {
            $this->assertStringContainsString('Obvod obdélníku s délkou', $example['equation']);
        }
    }

    public function testGenerateTriangle(): void
    {
        $examples = $this->generator->generate(1, 10, 3, 'triangle');
        $this->assertCount(3, $examples);

        foreach ($examples as $example) {
            $this->assertStringContainsString('Obvod trojúhelníku se stranami', $example['equation']);
        }
    }

    public function testGenerateCircle(): void
    {
        $examples = $this->generator->generate(1, 10, 3, 'circle');
        $this->assertCount(3, $examples);

        foreach ($examples as $example) {
            $this->assertStringContainsString('Obvod kruhu s poloměrem', $example['equation']);
        }
    }

    public function testSolveSquare(): void
    {
        $equation = "Obvod čtverce se stranou 5 cm";
        $solution = $this->generator->solve($equation, 'square');
        $this->assertEquals('20 cm', $solution['solution']);
        $this->assertCount(2, $solution['steps']);
    }

    public function testSolveRectangle(): void
    {
        $equation = "Obvod obdélníku s délkou 5 cm a šířkou 3 cm";
        $solution = $this->generator->solve($equation, 'rectangle');
        $this->assertEquals('16 cm', $solution['solution']);
        $this->assertCount(3, $solution['steps']);
    }

    public function testSolveTriangle(): void
    {
        $equation = "Obvod trojúhelníku se stranami 3 cm, 4 cm, a 5 cm";
        $solution = $this->generator->solve($equation, 'triangle');
        $this->assertEquals('12 cm', $solution['solution']);
        $this->assertCount(2, $solution['steps']);
    }

    public function testSolveCircle(): void
    {
        $equation = "Obvod kruhu s poloměrem 5 cm";
        $solution = $this->generator->solve($equation, 'circle');
        $expectedPerimeter = round(2 * pi() * 5, 2) . ' cm';
        $this->assertEquals($expectedPerimeter, $solution['solution']);
        $this->assertCount(2, $solution['steps']);
    }

    public function testVerifyCorrectResult(): void
    {
        $this->assertTrue($this->generator->verify(10.0, 10.0));
    }

    public function testVerifyIncorrectResult(): void
    {
        $this->assertFalse($this->generator->verify(15.0, 16.5));
    }
}
