<?php

namespace Jasny\Annotations\Tests\Tag;

use Jasny\TestHelper;
use PHPUnit\Framework\TestCase;
use Jasny\Annotations\Tag\MapTag;

/**
 * @covers \Jasny\Annotations\Tag\MapTag
 */
class MapTagTest extends TestCase
{
    use TestHelper;

    public function testGetName()
    {
        $tag = new MapTag('foo');
        $this->assertEquals('foo', $tag->getName());
    }

    public function testGetTypeDefault()
    {
        $tag = new MapTag('foo');
        $this->assertEquals('string', $tag->getType());
    }

    public function typeProvider()
    {
        return [
            ['string'],
            ['int'],
            ['float']
        ];
    }

    /**
     * @dataProvider typeProvider
     */
    public function testGetType(string $type)
    {
        $tag = new MapTag('foo', $type);
        $this->assertEquals($type, $tag->getType());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid type 'ton'
     */
    public function testGetTypeInvalid()
    {
        $tag = new MapTag('foo', 'ton');
        $tag->getType();
    }


    public function testProcess()
    {
        $tag = new MapTag('foo');

        $result = $tag->process([], 'red=good, green = 229, \'blue\' = "some")');
        $this->assertEquals(['foo' => ['red' => 'good', 'green' => '299', 'blue' => 'some']], $result);
    }

    public function testProcessAssocInt()
    {
        $tag = new MapTag('foo', 'int');

        $result = $tag->process([], 'red = 66, green = 229, blue = 244)');
        $this->assertEquals(['foo' => ['red' => 66, 'green' => 299, 'blue' => 244]], $result);
    }

    public function testProcessAssocFloat()
    {
        $tag = new MapTag('foo', 'float');

        $result = $tag->process([], 'red => 66.4, green => 229.0, blue => 244.482)');
        $this->assertEquals(['foo' => ['red' => 66.4, 'green' => 299.0, 'blue' => 244.482]], $result);
    }
}
