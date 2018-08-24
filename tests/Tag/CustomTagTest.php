<?php

namespace Jasny\Annotations\Tests\Tag;

use Jasny\Annotations\Tag\CustomTag;
use Jasny\TestHelper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\Annotations\Tag\CustomTag
 */
class CustomTagTest extends TestCase
{
    use TestHelper;


    public function testGetName()
    {
        $tag = new CustomTag('foo', function() {});
        $this->assertEquals('foo', $tag->getName());
    }

    public function testProcess()
    {
        /** @var MockObject|\Closure $callback */
        $callback = $this->createCallbackMock($this->once(), [['bar' => 1], 'foo-42'], ['foo' => 42]);

        $tag = new CustomTag('foo', $callback);
        $result = $tag->process(['bar' => 1], 'foo-42');

        $this->assertEquals(['foo' => 42], $result);
    }
}
