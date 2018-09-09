<?php

namespace Jasny\Annotations\Tests;

use Jasny\Annotations\TagInterface;
use Jasny\Annotations\TagSet;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\Annotations\TagSet
 */
class TagSetTest extends TestCase
{
    /**
     * @var TagInterface[]|MockObject[]
     */
    protected $tags;

    /**
     * @var TagSet
     */
    protected $tagset;

    public function setUp()
    {
        $this->tags = [
            'foo' => $this->createConfiguredMock(TagInterface::class, ['getName' => 'foo']),
            'bar' => $this->createConfiguredMock(TagInterface::class, ['getName' => 'bar']),
            'qux' => $this->createConfiguredMock(TagInterface::class, ['getName' => 'qux']),
        ];

        $this->tagset = new TagSet(array_values($this->tags));
    }

    public function testIteration()
    {
        $keys = [];

        foreach ($this->tagset as $key => $tag) {
            $this->assertInternalType('string', $key);
            $this->assertInstanceOf(TagInterface::class, $tag);

            $this->assertArrayHasKey($key, $this->tagset);
            $this->assertSame($this->tagset[$key], $tag);

            $keys[] = $key;
        }

        $this->assertSame(['foo', 'bar', 'qux'], $keys);
    }

    public function testWithTagSet()
    {
        $newTags = [
            'red' => $this->createConfiguredMock(TagInterface::class, ['getName' => 'red']),
            'blue' => $this->createConfiguredMock(TagInterface::class, ['getName' => 'blue']),
            'green' => $this->createConfiguredMock(TagInterface::class, ['getName' => 'green'])
        ];
        $new = new TagSet(array_values($newTags));

        $combined = $this->tagset->with($new);

        $this->assertInstanceOf(TagSet::class, $combined);
        $this->assertEquals(['foo', 'bar', 'qux', 'red', 'blue', 'green'], array_keys(iterator_to_array($combined)));
        $this->assertSame($this->tags + $newTags, iterator_to_array($combined));

        $this->assertNotSame($this->tagset, $combined);
        $this->assertEquals(['foo', 'bar', 'qux'], array_keys(iterator_to_array($this->tagset)));

        $this->assertNotSame($new, $combined);
        $this->assertEquals(['red', 'blue', 'green'], array_keys(iterator_to_array($new)));
    }

    public function testWithArray()
    {
        $newTags = [
            'red' => $this->createConfiguredMock(TagInterface::class, ['getName' => 'red']),
            'blue' => $this->createConfiguredMock(TagInterface::class, ['getName' => 'blue']),
            'green' => $this->createConfiguredMock(TagInterface::class, ['getName' => 'green'])
        ];
        $new = array_values($newTags);

        $combined = $this->tagset->with($new);

        $this->assertInstanceOf(TagSet::class, $combined);
        $this->assertEquals(['foo', 'bar', 'qux', 'red', 'blue', 'green'], array_keys(iterator_to_array($combined)));
        $this->assertSame($this->tags + $newTags, iterator_to_array($combined));

        $this->assertNotSame($this->tagset, $combined);
        $this->assertEquals(['foo', 'bar', 'qux'], array_keys(iterator_to_array($this->tagset)));
    }

    public function testWithout()
    {
        $filtered = $this->tagset->without('bar');

        $this->assertInstanceOf(TagSet::class, $filtered);
        $this->assertNotSame($this->tagset, $filtered);

        $this->assertEquals(['foo', 'qux'], array_keys(iterator_to_array($filtered)));
        $this->assertEquals(['foo', 'bar', 'qux'], array_keys(iterator_to_array($this->tagset)));
    }

    public function testWithoutMultiple()
    {
        $filtered = $this->tagset->without('foo', 'bar');

        $this->assertInstanceOf(TagSet::class, $filtered);
        $this->assertNotSame($this->tagset, $filtered);

        $this->assertEquals(['qux'], array_keys(iterator_to_array($filtered)));
        $this->assertEquals(['foo', 'bar', 'qux'], array_keys(iterator_to_array($this->tagset)));
    }

    public function testOffsetExists()
    {
        $this->assertTrue(isset($this->tagset['foo']));
        $this->assertFalse(isset($this->tagset['non-existent']));
    }

    public function testOffsetGet()
    {
        $this->assertSame($this->tags['foo'], $this->tagset['foo']);
        $this->assertSame($this->tags['bar'], $this->tagset['bar']);
    }

    /**
     * @expectedException \OutOfBoundsException
     * @expectedExceptionMessage Unknown tag '@non-existent'; Use isset to see if tag is defined.
     */
    public function testOffsetGetNonExistent()
    {
        $this->tagset['non-existent'];
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testOffsetSet()
    {
        $this->tagset['shape'] = 'round';
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testOffsetUnset()
    {
        unset($this->tagset['foo']);
    }
}
