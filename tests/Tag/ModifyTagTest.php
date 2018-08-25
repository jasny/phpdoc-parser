<?php

namespace Jasny\Annotations\Tests\Tag;

use Jasny\Annotations\Tag\ModifyTag;
use Jasny\Annotations\TagInterface;
use Jasny\TestHelper;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Jasny\Annotations\Tag\ModifyTag
 */
class ModifyTagTest extends TestCase
{
    use TestHelper;

    public function testGetName()
    {
        /** @var MockObject|TagInterface $mockTag */
        $mockTag = $this->createConfiguredMock(TagInterface::class, ['getName' => 'foo']);

        $tag = new ModifyTag($mockTag, function() {});

        $this->assertEquals('foo', $tag->getName());
    }

    public function testGetTag()
    {
        /** @var MockObject|TagInterface $mockTag */
        $mockTag = $this->createMock(TagInterface::class);

        $tag = new ModifyTag($mockTag, function() {});

        $this->assertSame($mockTag, $tag->getTag());
    }

    public function testProcess()
    {
        /** @var MockObject|TagInterface $mockTag */
        $mockTag = $this->createMock(TagInterface::class);
        $mockTag->expects($this->once())->method('process')->with([], 'one two')
            ->willReturn(['foo' => 42]);

        /** @var MockObject|callable $apply */
        $args = [['bar' => 1], ['foo' => 42], 'one two'];
        $apply = $this->createCallbackMock($this->once(), $args, ['bar' => 2, 'foo' => 3]);

        $tag = new ModifyTag($mockTag, $apply);

        $result = $tag->process(['bar' => 1], 'one two');

        $this->assertEquals(['bar' => 2, 'foo' => 3], $result);
    }
}
