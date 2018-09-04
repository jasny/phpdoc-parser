<?php

namespace Jasny\Annotations\Tests\Tag;

use Jasny\Annotations\Tag\FlagTag;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\Annotations\Tag\FlagTag
 * @covers \Jasny\Annotations\Tag\AbstractTag
 */
class FlagTagTest extends TestCase
{
    public function testGetName()
    {
        $tag = new FlagTag('foo');
        $this->assertEquals('foo', $tag->getName());
    }

    public function testProcess()
    {
        $tag = new FlagTag('foo');
        $result = $tag->process(['bar' => 42], '');

        $this->assertEquals(['bar' => 42, 'foo' => true], $result);
    }

    public function testProcessDescription()
    {
        $tag = new FlagTag('foo');
        $result = $tag->process([], 'this is ignored');

        $this->assertEquals(['foo' => true], $result);
    }
}
