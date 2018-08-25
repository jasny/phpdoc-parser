<?php

namespace Jasny\Annotations\Tests\Tag;

use Jasny\Annotations\Tag\MultiTag;
use Jasny\Annotations\TagInterface;
use Jasny\TestHelper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\Annotations\Tag\MultiTag
 */
class MultiTagTest extends TestCase
{
    use TestHelper;

    public function testGetName()
    {
        /** @var MockObject|TagInterface $mockTag */
        $mockTag = $this->createConfiguredMock(TagInterface::class, ['getName' => 'foo']);

        $tag = new MultiTag('foos', $mockTag);

        $this->assertEquals('foo', $tag->getName());
    }

    public function testGetKey()
    {
        /** @var MockObject|TagInterface $mockTag */
        $mockTag = $this->createConfiguredMock(TagInterface::class, ['getName' => 'foo']);

        $tag = new MultiTag('foos', $mockTag);

        $this->assertEquals('foos', $tag->getKey());
    }

    public function testGetTag()
    {
        /** @var MockObject|TagInterface $mockTag */
        $mockTag = $this->createMock(TagInterface::class);

        $tag = new MultiTag('foos', $mockTag);

        $this->assertSame($mockTag, $tag->getTag());
    }

    public function testProcess()
    {
        /** @var MockObject|TagInterface $mockTag */
        $mockTag = $this->createMock(TagInterface::class);
        $mockTag->expects($this->once())->method('process')->with([], 'three')
            ->willReturn(['foo' => '3']);

        $tag = new MultiTag('foos', $mockTag);

        $result = $tag->process(['foos' => ['one', 'two']], 'three');

        $this->assertEquals(['foos' => ['one', 'two', '3']], $result);
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Unable to parse '@foo' tag: Multi tags must result in exactly one annotation per tag.
     */
    public function testProcessLogicException()
    {
        /** @var MockObject|TagInterface $mockTag */
        $mockTag = $this->createConfiguredMock(TagInterface::class, ['getName' => 'foo']);
        $mockTag->expects($this->once())->method('process')->with([], 'three')
            ->willReturn(['foo' => '3', 'bar' => '2']);

        $tag = new MultiTag('foos', $mockTag);

        $tag->process(['foos' => ['one', 'two']], 'three');
    }

    public function testProcessKey()
    {
        /** @var MockObject|TagInterface $mockTag */
        $mockTag = $this->createMock(TagInterface::class);
        $mockTag->expects($this->once())->method('process')->with([], 'goodbye')
            ->willReturn(['foo' => ['name' => 'two', 'desc' => 'bye']]);

        $tag = new MultiTag('foos', $mockTag, 'name');

        $result = $tag->process(['foos' => ['one' => ['name' => 'one', 'desc' => 'hi']]], 'goodbye');

        $this->assertEquals(['foos' => [
            'one' => ['name' => 'one', 'desc' => 'hi'],
            'two' => ['name' => 'two', 'desc' => 'bye']
        ]], $result);
    }

    /**
     * @expectedException \Jasny\Annotations\AnnotationException
     * @expectedExceptionMessage Unable to add '@foo goodbye' tag: No name
     */
    public function testProcessKeyUnkonwn()
    {
        /** @var MockObject|TagInterface $mockTag */
        $mockTag = $this->createConfiguredMock(TagInterface::class, ['getName' => 'foo']);
        $mockTag->expects($this->once())->method('process')->with([], 'goodbye')
            ->willReturn(['foo' => ['desc' => 'bye']]);

        $tag = new MultiTag('foos', $mockTag, 'name');

        $tag->process(['foos' => ['one' => ['name' => 'one', 'desc' => 'hi']]], 'goodbye');
    }

    /**
     * @expectedException \Jasny\Annotations\AnnotationException
     * @expectedExceptionMessage Unable to add '@foo goodbye' tag: Duplicate name 'one'
     */
    public function testProcessKeyDuplicate()
    {
        /** @var MockObject|TagInterface $mockTag */
        $mockTag = $this->createConfiguredMock(TagInterface::class, ['getName' => 'foo']);
        $mockTag->expects($this->once())->method('process')->with([], 'goodbye')
            ->willReturn(['foo' => ['name' => 'one', 'desc' => 'bye']]);

        $tag = new MultiTag('foos', $mockTag, 'name');

        $tag->process(['foos' => ['one' => ['name' => 'one', 'desc' => 'hi']]], 'goodbye');
    }
}
