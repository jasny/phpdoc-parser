<?php

namespace Jasny\Annotations\Tests\Tag;

use PHPUnit\Framework\TestCase;
use Jasny\Annotations\Tag\RegExpTag;

/**
 * @covers \Jasny\Annotations\Tag\RegExpTag
 * @covers \Jasny\Annotations\Tag\AbstractTag
 */
class RegExpTagTest extends TestCase
{
    use \Jasny\TestHelper;

    /**
     * Test 'getRegExp' method
     */
    public function testGetRegExp()
    {
        $tag = new RegExpTag('foo', 'foo_regexp');
        $result = $tag->getRegExp();

        $this->assertSame('foo_regexp', $result);
    }

    /**
     * Provide data for testing 'process' method
     *
     * @return array
     */
    public function processProvider()
    {
        return [
            ['//', 'foo string to parse', ['some' => 'value', 'foo' => ['']]],
            ['/.*/', 'foo string to parse', ['some' => 'value', 'foo' => ['foo string to parse']]],
            ['/\s+(\S+)/', 'foo string to parse', ['some' => 'value', 'foo' => [' string', 'string']]],
        ];
    }

    /**
     * Test 'process' method
     *
     * @dataProvider processProvider
     */
    public function testProcess($regexp, $value, $expected)
    {
        $tag = new RegExpTag('foo', $regexp);
        $result = $tag->process(['some' => 'value'], $value);

        $this->assertSame($expected, $result);
    }

    /**
     * Test 'process' method, if exception should be thrown
     *
     * @expectedException Jasny\Annotations\AnnotationException
     * @expectedExceptionMessage Failed to parse '@foo not-abc': invalid syntax
     */
    public function testProcessException()
    {
        $tag = new RegExpTag('foo', '/^abc/');
        $result = $tag->process(['some' => 'value'], 'not-abc');
    }
}
