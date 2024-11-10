<?php

use PHPUnit\Framework\TestCase;
use App\Generators\InequalityGenerator;

class InequalityGeneratorTest extends TestCase
{
    private InequalityGenerator $generator;

    protected function setUp(): void
    {
        $this->generator = new InequalityGenerator();
    }

    public function testGenerateEasyDifficulty()
    {
        $examples = $this->generator->generate(1, 10, 5, 'easy');
        $this->assertCount(5, $examples);

        foreach ($examples as $example) {
            $this->assertArrayHasKey('equation', $example);
            $this->assertArrayHasKey('correctResult', $example);
            $this->assertArrayHasKey('inequalitySign', $example);
            $this->assertArrayHasKey('steps', $example);

            $this->assertMatchesRegularExpression('/\d+x \+ \d+ [><] \d+/', $example['equation']);
        }
    }

    public function testGenerateMediumDifficulty()
    {
        $examples = $this->generator->generate(1, 10, 5, 'medium');
        $this->assertCount(5, $examples);

        foreach ($examples as $example) {
            $this->assertArrayHasKey('equation', $example);
            $this->assertArrayHasKey('correctResult', $example);
            $this->assertArrayHasKey('inequalitySign', $example);
            $this->assertArrayHasKey('steps', $example);

            $this->assertMatchesRegularExpression('/\d+x \+ \d+ [><] \d+/', $example['equation']);
        }
    }

    public function testGenerateHardDifficulty()
    {
        $examples = $this->generator->generate(1, 10, 5, 'hard');
        $this->assertCount(5, $examples);

        foreach ($examples as $example) {
            $this->assertArrayHasKey('equation', $example);
            $this->assertArrayHasKey('correctResult', $example);
            $this->assertArrayHasKey('inequalitySign', $example);
            $this->assertArrayHasKey('steps', $example);

            $this->assertMatchesRegularExpression('/\-?\d+x \+ \-?\d+ [><] \-?\d+/', $example['equation']);
        }
    }

    public function testGenerateInvalidDifficulty()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->generator->generate(1, 10, 5, 'invalid_difficulty');
    }

    public function testVerifyGreaterThan()
    {
        $correctResult = [
            'correctResult' => 5,
            'inequalitySign' => '>'
        ];
        $this->assertTrue($this->generator->verify(6, $correctResult));
        $this->assertFalse($this->generator->verify(4, $correctResult));
    }

    public function testVerifyLessThan()
    {
        $correctResult = [
            'correctResult' => 5,
            'inequalitySign' => '<'
        ];
        $this->assertTrue($this->generator->verify(4, $correctResult));
        $this->assertFalse($this->generator->verify(6, $correctResult));
    }
}
