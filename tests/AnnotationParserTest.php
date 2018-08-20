<?php

namespace Jasny\Annotations\Tests;

use Jasny\Annotations\AnnotationParser;
use Jasny\Annotations\TagInterface;
use Jasny\Annotations\TagSet;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AnnotationParserTest extends TestCase
{
    /**
     * @var TagSet|TagInterface[]|MockObject[]
     */
    protected $tags;

    /**
     * @var AnnotationParser
     */
    protected $parser;

    public function setUp()
    {
        $fooTag = $this->createMock(TagInterface::class);
        $fooTag->method('getName')->willReturn('foo');

        $barTag = $this->createMock(TagInterface::class);
        $barTag->method('getName')->willReturn('bar');

        $quxTag = $this->createMock(TagInterface::class);
        $quxTag->method('getName')->willReturn('qux');

        $this->tags = new TagSet([$fooTag, $barTag, $quxTag]);
        $this->parser = new AnnotationParser($this->tags);
    }

    public function testParseFlag()
    {
        $this->tags['foo']->expects($this->once())->method('process')
            ->with([], '');

        $doc = <<<DOC
/**
 * @foo
 */
DOC;

        $this->parser->parse($doc);
    }

    public function testParseMultiple()
    {
        $this->tags['foo']->expects($this->exactly(3))->method('process')
            ->withConsecutive([[], ''], [['foo' => 1], 'hi'], [['foo' => 2, 'desc' => 'hi'], ''])
            ->willReturnOnConsecutiveCalls(['foo' => 1], ['foo' => 2, 'desc' => 'hi'], ['foo' => 3, 'desc' => 'hi']);

        $doc = <<<DOC
/**
 * @foo
 * @foo hi
 * @foo
 */
DOC;

        $result = $this->parser->parse($doc);

        $this->assertSame(['foo' => 3, 'desc' => 'hi'], $result);
    }
}
