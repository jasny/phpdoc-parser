<?php

namespace Jasny\Annotations\Tests\Tag;

use Jasny\Annotations\Tag\DescriptionTag;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\Annotations\Tag\DescriptionTag
 * @covers \Jasny\Annotations\Tag\AbstractTag
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
