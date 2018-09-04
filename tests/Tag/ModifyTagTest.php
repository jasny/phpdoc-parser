<?php

namespace Jasny\Annotations\Tests\Tag;

use PHPUnit\Framework\TestCase;
use Jasny\Annotations\Tag\ModifyTag;
use Jasny\Annotations\TagInterface;

/**
 * @covers \Jasny\Annotations\Tag\ModifyTag
 */
class ModifyTagTest extends TestCase
{
    use \Jasny\TestHelper;

    /**
     * Test 'getName' method
     */
    public function testGetName()
    {
        $innerTag = $this->createMock(TagInterface::class);
        $innerTag->expects($this->once())->method('getName')->willReturn('foo');

        $tag = new ModifyTag($innerTag, function() {});

        $result = $tag->getName();

        $this->assertSame('foo', $result);
    }

    /**
     * Test 'geTag' method
     */
    public function testGeTag()
    {
        $innerTag = $this->createMock(TagInterface::class);
        $tag = new ModifyTag($innerTag, function() {});

        $result = $tag->getTag();

        $this->assertSame($innerTag, $result);
    }

    /**
     * Test 'process' method
     */
    public function testProcess()
    {
        $value = 'some_value';
        $annotations = ['bar' => 'bar_value'];
        $tagAnnotations = ['foo' => 'foo_value'];
        $expected = ['bar' => 'bar_value', 'foo' => 'foo_value', 'more' => 'added'];

        $innerTag = $this->createMock(TagInterface::class);
        $innerTag->expects($this->once())->method('process')->with([], $value)->willReturn($tagAnnotations);

        $postProcess = function($annotationsParam, $tagAnnotationsParam, $valueParam) use ($annotations, $tagAnnotations, $value) {
            $this->assertSame($annotations, $annotationsParam);
            $this->assertSame($tagAnnotations, $tagAnnotationsParam);
            $this->assertSame($value, $valueParam);

            return array_merge($annotations, $tagAnnotations, ['more' => 'added']);

            $annotationsParam['more'] = 'added';

            return $annotationsParam;
        };

        $postProcess->bindTo($this);

        $tag = new ModifyTag($innerTag, $postProcess);
        $result = $tag->process($annotations, $value);

        $this->assertSame($expected, $result);
    }
}
