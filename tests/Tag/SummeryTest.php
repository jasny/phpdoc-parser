<?php

namespace Jasny\PhpdocParser\Tests\Tag;

use Jasny\PhpdocParser\Tag\Summery;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\PhpdocParser\Tag\Summery
 * @covers \Jasny\PhpdocParser\Tag\AbstractTag
 */
class SummeryTest extends TestCase
{
    /**
     * Test 'getName' method
     */
    public function testGetName()
    {
        $tag = new Summery();
        $result = $tag->getName();

        $this->assertSame('summery', $result);
    }

    /**
     * Provide data for testing 'process' method
     *
     * @return array
     */
    public function processProvider()
    {
        $doc1 = <<<DOC
/**
 * Have summery here.
 */
DOC;

        $doc2 = <<<DOC
/**
 * Have summery here.
 * And a description
 * for a few lines
 * of words.
 */
DOC;

        $doc3 = <<<DOC
/**
 * Have summery here.
 *
 * And a description
 * for a few lines
 * of words.
 */
DOC;

        $doc4 = <<<DOC
/**
 * Have summery here.
 *
 * And a description
 *  for a few lines
 *  of words.
 */
DOC;

        $doc5 = <<<DOC
/**
 * Have summery here.
 *
 * And a description
 *  for a few lines
 *  of words.
 *
 * @param int
 * @return Foo
 */
DOC;

        $doc6 = <<<DOC
/**
 *
 */
DOC;

        $doc7 = <<<DOC
/**
 * @param int
 * @return Foo
 */
DOC;

        return [
            [
                $doc1,
                ['some' => 'value', 'summery' => 'Have summery here.', 'description' => "Have summery here."]
            ],
            [
                $doc2,
                ['some' => 'value', 'summery' => 'Have summery here.', 'description' => "Have summery here.\nAnd a description\nfor a few lines\nof words."]
            ],
            [
                $doc3,
                ['some' => 'value', 'summery' => 'Have summery here.', 'description' => "Have summery here.\nAnd a description\nfor a few lines\nof words."]
            ],
            [
                $doc4,
                ['some' => 'value', 'summery' => 'Have summery here.', 'description' => "Have summery here.\nAnd a description\nfor a few lines\nof words."]
            ],
            [
                $doc5,
                ['some' => 'value', 'summery' => 'Have summery here.', 'description' => "Have summery here.\nAnd a description\nfor a few lines\nof words."]
            ],
            [
                $doc6,
                ['some' => 'value']
            ],
            [
                $doc7,
                ['some' => 'value']
            ],
        ];
    }

    /**
     * Test 'process' method
     *
     * @dataProvider processProvider
     */
    public function testProcess($doc, $expected)
    {
        $tag = new Summery();
        $result = $tag->process(['some' => 'value'], $doc);

        $this->assertSame($expected, $result);
    }
}
