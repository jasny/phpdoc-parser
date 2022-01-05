<?php

namespace Jasny\PhpdocParser\Tests\Tag;

use Jasny\PHPUnit\CallbackMockTrait;
use Jasny\PhpdocParser\Tag\CustomTag;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\PhpdocParser\Tag\CustomTag
 * @covers \Jasny\PhpdocParser\Tag\AbstractTag
 */
class CustomTagTest extends TestCase
{
    use CallbackMockTrait;

    public function testGetName()
    {
        $tag = new CustomTag('foo', function() {});
        $this->assertEquals('foo', $tag->getName());
    }

    public function testProcess()
    {
        $callback = $this->createCallbackMock($this->once(), [['bar' => 1], 'foo-42'], ['foo' => 42]);

        $tag = new CustomTag('foo', $callback);
        $result = $tag->process(['bar' => 1], 'foo-42');

        $this->assertEquals(['foo' => 42], $result);
    }
}
