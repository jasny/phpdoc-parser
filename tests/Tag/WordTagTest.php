<?php

namespace Jasny\PhpdocParser\Tests\Tag;

use Jasny\PhpdocParser\Tag\WordTag;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\PhpdocParser\Tag\WordTag
 * @covers \Jasny\PhpdocParser\Tag\AbstractTag
 */
class WordTagTest extends TestCase
{
    public function testGetDefault()
    {
        $tag = new WordTag('foo', true);
        $this->assertSame(true, $tag->getDefault());
    }

    public function testGetDefaultUnspecified()
    {
        $tag = new WordTag('foo');
        $this->assertSame('', $tag->getDefault());
    }

    public function testGetName()
    {
        $tag = new WordTag('foo');
        $this->assertEquals('foo', $tag->getName());
    }

    public function testProcess()
    {
        $tag = new WordTag('foo', true);

        $result = $tag->process(['bar' => 1], 'hi');
        $this->assertEquals(['bar' => 1, 'foo' => 'hi'], $result);
    }

    public function testProcessDefault()
    {
        $tag = new WordTag('foo', true);

        $result = $tag->process([], '');
        $this->assertEquals(['foo' => true], $result);
    }

    public function testProcessSentence()
    {
        $tag = new WordTag('foo');

        $result = $tag->process([], 'hello sweet world');
        $this->assertEquals(['foo' => 'hello'], $result);
    }

    public function quoteProvider()
    {
        return [
            ['"hello world" This a sentence.'],
            ['\'hello world\' This a sentence.']
        ];
    }

    /**
     * @dataProvider quoteProvider
     */
    public function testProcessQuote($value)
    {
        $tag = new WordTag('foo');

        $result = $tag->process([], $value);
        $this->assertEquals(['foo' => 'hello world'], $result);
    }
}
