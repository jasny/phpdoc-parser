<?php

namespace Jasny\Annotations\Tests\Tag;

use PHPUnit\Framework\TestCase;
use Jasny\Annotations\Tag\MapTag;

/**
 * @covers \Jasny\Annotations\Tag\MapTag
 */
class MapTagTest extends TestCase
{

    public function testGetName()
    {
        $tag = new MapTag('foo');
        $this->assertEquals('foo', $tag->getName());
    }

    public function testProcess()
    {
        $tag = new MapTag('foo', true);

        $result = $tag->process([], 'red=good, green = 229, \'blue\' = "some")');
        $this->assertEquals(['foo' => ['red' => 'good', 'green' => '299', 'blue' => 'some']], $result);
    }

    public function testProcessAssocInt()
    {
        $tag = new MapTag('foo', true, 'int');

        $result = $tag->process([], 'red = 66, green = 229, blue = 244)');
        $this->assertEquals(['foo' => ['red' => 66, 'green' => 299, 'blue' => 244]], $result);
    }

    public function testProcessAssocFloat()
    {
        $tag = new MapTag('foo', true, 'float');

        $result = $tag->process([], 'red => 66.4, green => 229.0, blue => 244.482)');
        $this->assertEquals(['foo' => ['red' => 66.4, 'green' => 299.0, 'blue' => 244.482]], $result);
    }
}
