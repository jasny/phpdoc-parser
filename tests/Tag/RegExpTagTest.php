<?php

namespace Jasny\PhpdocParser\Tests\Tag;

use PHPUnit\Framework\TestCase;
use Jasny\PhpdocParser\Tag\RegExpTag;
use Jasny\PhpdocParser\PhpdocException;

/**
 * @covers \Jasny\PhpdocParser\Tag\RegExpTag
 * @covers \Jasny\PhpdocParser\Tag\AbstractTag
 */
class RegExpTagTest extends TestCase
{
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
     */
    public function testProcessException()
    {
        $tag = new RegExpTag('foo', '/^abc/');
        
        $this->expectException(PhpdocException::class);
        $this->expectExceptionMessage("Failed to parse '@foo not-abc': invalid syntax");
        
        $result = $tag->process(['some' => 'value'], 'not-abc');
    }
}
