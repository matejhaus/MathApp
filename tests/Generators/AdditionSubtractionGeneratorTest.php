<?php

namespace App\Tests\Generators;

use App\Generators\AdditionSubtractionGenerator;
use PHPUnit\Framework\TestCase;

class AdditionSubtractionGeneratorTest extends TestCase
{
    private AdditionSubtractionGenerator $generator;

    protected function setUp(): void
    {
        $this->generator = new AdditionSubtractionGenerator();
    }

    public function testGenerateReturnsCorrectFormat()
    {
        $examples = $this->generator->generate(1, 10, 5, 'easy');

        $this->assertCount(5, $examples);

        foreach ($examples as $example) {
            $this->assertArrayHasKey('equation', $example);
            $this->assertArrayHasKey('correctResult', $example);
            $this->assertArrayHasKey('steps', $example);
            $this->assertIsString($example['equation']);
            $this->assertIsArray($example['steps']);
        }
    }

    public function testSolveReturnsCorrectSolution()
    {
        $equation = '3 + 5 - 2';
        $result = $this->generator->solve($equation, 'easy');

        $this->assertEquals(6, $result['solution']);
        $this->assertCount(4, $result['steps']);
    }

    public function testVerifyReturnsTrueForCorrectResult()
    {
        $result = $this->generator->verify(6, 6);
        $this->assertTrue($result);
    }

    public function testVerifyReturnsFalseForIncorrectResult()
    {
        $result = $this->generator->verify(5, 6);
        $this->assertFalse($result);
    }

    public function testCalculateResultWithMixedOperations()
    {
        $numbers = [3, 5, 2];
        $operations = ['+', '-'];

        $result = $this->invokeMethod($this->generator, 'calculateResult', [$numbers, $operations]);
        $this->assertEquals(6, $result);
    }

    public function testGenerateStepsReturnsCorrectSteps()
    {
        $numbers = [3, 5, 2];
        $operations = ['+', '-'];

        $steps = $this->invokeMethod($this->generator, 'generateSteps', [$numbers, $operations]);

        $this->assertCount(4, $steps);
        $this->assertStringContainsString('Máme příklad:', $steps[0]);
        $this->assertStringContainsString('Výsledek je:', $steps[3]);
    }

    private function invokeMethod($object, $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}
