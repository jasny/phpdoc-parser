<?php

namespace Jasny\PhpdocParser\Tests\Tag;

use Jasny\PhpdocParser\Tag\DescriptionTag;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\PhpdocParser\Tag\DescriptionTag
 * @covers \Jasny\PhpdocParser\Tag\AbstractTag
 */
class DescriptionTagTest extends TestCase
{
    public function testGetName()
    {
        $tag = new DescriptionTag('foo');
        $this->assertEquals('foo', $tag->getName());
    }

    public function testProcess()
    {
        $tag = new DescriptionTag('foo');

        $result = $tag->process(['bar' => 1], 'Hello this is "the text"');
        $this->assertEquals(['bar' => 1, 'foo' => 'Hello this is "the text"'], $result);
    }
}
