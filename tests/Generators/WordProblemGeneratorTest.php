<?php

namespace App\Tests\Generators;

use App\Generators\WordProblemGenerator;
use PHPUnit\Framework\TestCase;

class WordProblemGeneratorTest extends TestCase
{
    public function testGenerateEasyDifficulty()
    {
        $generator = new WordProblemGenerator();
        $examples = $generator->generate(1, 10, 5, 'easy');

        $this->assertCount(5, $examples);
        foreach ($examples as $example) {
            $this->assertIsArray($example);
            $this->assertArrayHasKey('equation', $example);
            $this->assertArrayHasKey('format', $example);
        }
    }

    public function testSolveAdditionProblem()
    {
        $generator = new WordProblemGenerator();
        $equation = "Na farmě bylo 5 jablek. Poté farmář přidal 3 jablek. Kolik jablek má nyní?";
        $result = $generator->solve($equation);

        $this->assertEquals(8, $result['solution']);
        $this->assertStringContainsString("Vypočítáme: 5 + 3 = 8", $result['steps'][0]);
    }

    public function testSolveSubtractionProblem()
    {
        $generator = new WordProblemGenerator();
        $equation = "V krabici bylo 10 bonbónů. Pokud si vezmeme 3 bonbónů, kolik bonbónů zůstane?";
        $result = $generator->solve($equation);

        $this->assertEquals(7, $result['solution']);
        $this->assertStringContainsString("Vypočítáme: 10 - 3 = 7", $result['steps'][0]);
    }

    public function testSolveMultiplicationProblem()
    {
        $generator = new WordProblemGenerator();
        $equation = "Každý balíček obsahuje 4 sušenek. Kolik sušenek je v 5 balíčkách?";
        $result = $generator->solve($equation);

        $this->assertEquals(20, $result['solution']);
        $this->assertStringContainsString("Vypočítáme: 4 * 5 = 20", $result['steps'][0]);
    }

    public function testSolveDivisionProblem()
    {
        $generator = new WordProblemGenerator();
        $equation = "Máme 20 jablek, která chceme rozdělit mezi 4 přátel. Kolik jablek dostane každý přítel?";
        $result = $generator->solve($equation);

        $this->assertEquals(5, $result['solution']);
        $this->assertStringContainsString("Vypočítáme: 20 / 4 = 5", $result['steps'][0]);
    }

    public function testVerifyCorrectAnswer()
    {
        $generator = new WordProblemGenerator();
        $correctResult = 5.0;
        $this->assertTrue($generator->verify(5.0, $correctResult));
        $this->assertTrue($generator->verify(5.01, $correctResult));
    }

    public function testVerifyIncorrectAnswer()
    {
        $generator = new WordProblemGenerator();
        $correctResult = 5.0;
        $this->assertFalse($generator->verify(4.98, $correctResult));
    }
}
