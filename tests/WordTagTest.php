<?php

namespace Jasny\Annotations\Tests;

use Jasny\Annotations\Tag\WordTag;
use PHPUnit\Framework\TestCase;

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
        $tag = new WordTag('foo', true);

        $result = $tag->process([], 'hello sweet world');
        $this->assertEquals(['foo' => 'hello'], $result);
    }

    public function testProcessReplace()
    {
        $tag = new WordTag('foo', true);

        $result = $tag->process(['foo' => 'hi'], 'bye');
        $this->assertEquals(['foo' => 'bye'], $result);
    }
}
