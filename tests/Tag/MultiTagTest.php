<?php

namespace Jasny\Annotations\Tests\Tag;

use Jasny\Annotations\Tag\MultiTag;
use Jasny\Annotations\TagInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\Annotations\Tag\MultiTag
 */
class MultiTagTest extends TestCase
{
    /**
     * Test 'getKey' method
     */
    public function testGetKey()
    {
        $tags = new MultiTag('foo', $this->createMock(TagInterface::class));
        $result = $tags->getKey();

        $this->assertSame('foo', $result);
    }

    /**
     * Test 'getName' method
     */
    public function testGetName()
    {
        $tag = $this->createMock(TagInterface::class);
        $tag->expects($this->once())->method('getName')->willReturn('bar');

        $tags = new MultiTag('foo', $tag);
        $result = $tags->getName();

        $this->assertSame('bar', $result);
    }

    /**
     * Test 'getTag' method
     */
    public function testGetTag()
    {
        $tag = $this->createMock(TagInterface::class);

        $tags = new MultiTag('foo', $tag);
        $result = $tags->getTag();

        $this->assertSame($tag, $result);
    }

    /**
     * Test 'process' method
     */
    public function testProcess()
    {
        $value = 'foo comment to process';
        $tagAnnotations = ['inner_tag_name' => ['foo' => 'comment', 'to' => 'process']];

        $tag = $this->createMock(TagInterface::class);
        $tag->method('process')->with([], $value)->willReturn($tagAnnotations);
        $tag->method('getName')->willReturn('inner_tag_name');

        $tags = new MultiTag('multi_tag_container', $tag, 'foo');
        $result = $tags->process([
            'multi_tag_container' => ['prev_tag' => ['some' => 'value']]
        ], $value);

        $expected = [
            'multi_tag_container' => [
                'prev_tag' => ['some' => 'value'],
                'comment' => ['foo' => 'comment', 'to' => 'process']
            ]
        ];

        $this->assertSame($expected, $result);
    }

    /**
     * Test 'process' method, if no index is set
     */
    public function testProcessNoIndex()
    {
        $value = 'foo comment to process';
        $tagAnnotations = ['inner_tag_name' => ['foo' => 'comment', 'to' => 'process']];

        $tag = $this->createMock(TagInterface::class);
        $tag->method('process')->with([], $value)->willReturn($tagAnnotations);
        $tag->method('getName')->willReturn('inner_tag_name');

        $tags = new MultiTag('multi_tag_container', $tag);
        $result = $tags->process([
            'multi_tag_container' => [['some' => 'value']]
        ], $value);

        $expected = [
            'multi_tag_container' => [
                ['some' => 'value'],
                ['foo' => 'comment', 'to' => 'process']
            ]
        ];

        $this->assertSame($expected, $result);
    }

    /**
     * Test 'process' method, if processing inner tag returns array with > 1 elements
     *
     * @expectedException Jasny\Annotations\AnnotationException
     * @expectedExceptionMessage Unable to parse '@inner_tag_name foo comment to process' tag: Multi tags must result in exactly one annotations per tag.
     */
    public function testProcessTagAnnotationCountException()
    {
        $value = 'foo comment to process';
        $tagAnnotations = ['foo' => 'comment', 'to' => 'process'];

        $tag = $this->createMock(TagInterface::class);
        $tag->method('process')->with([], $value)->willReturn($tagAnnotations);
        $tag->method('getName')->willReturn('inner_tag_name');

        $tags = new MultiTag('multi_tag_container', $tag, 'foo');
        $result = $tags->process([
            'multi_tag_container' => ['prev_tag' => ['some' => 'value']]
        ], $value);
    }

    /**
     * Test 'process' method, in case when processing inner tag returns wrong data
     *
     * @expectedException Jasny\Annotations\AnnotationException
     * @expectedExceptionMessage Unable to add '@inner_tag_name foo comment to process' tag: No non_exist_index
     */
    public function testProcessWrongTagAnnotations()
    {
        $value = 'foo comment to process';
        $tagAnnotations = ['inner_tag_name' => ['another' => 'key']];

        $tag = $this->createMock(TagInterface::class);
        $tag->method('process')->with([], $value)->willReturn($tagAnnotations);
        $tag->method('getName')->willReturn('inner_tag_name');

        $tags = new MultiTag('multi_tag_container', $tag, 'non_exist_index');
        $result = $tags->process([
            'multi_tag_container' => ['prev_tag' => ['some' => 'value']]
        ], $value);
    }

    /**
     * Test 'process' method, in case when data with given index already exists in annotations
     *
     * @expectedException Jasny\Annotations\AnnotationException
     * @expectedExceptionMessage Unable to add '@inner_tag_name foo comment to process' tag: Duplicate exist_index
     */
    public function testProcessDuplicateIndex()
    {
        $value = 'foo comment to process';
        $tagAnnotations = ['inner_tag_name' => ['exist_index' => 'value']];

        $tag = $this->createMock(TagInterface::class);
        $tag->method('process')->with([], $value)->willReturn($tagAnnotations);
        $tag->method('getName')->willReturn('inner_tag_name');

        $tags = new MultiTag('multi_tag_container', $tag, 'exist_index');
        $result = $tags->process([
            'multi_tag_container' => ['value' => ['some_other' => 'some_value']]
        ], $value);
    }
}
