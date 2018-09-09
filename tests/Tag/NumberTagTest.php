<?php

namespace Jasny\PhpdocParser\Tests\Tag;

use PHPUnit\Framework\TestCase;
use Jasny\PhpdocParser\Tag\NumberTag;
use Jasny\PhpdocParser\PhpdocException;
use TypeError;

/**
 * @covers \Jasny\PhpdocParser\Tag\NumberTag
 * @covers \Jasny\PhpdocParser\Tag\AbstractTag
 */
class NumberTagTest extends TestCase
{
    use \Jasny\TestHelper;

    /**
     * Provide data for testing '__construct' method
     *
     * @return array
     */
    public function constructProvider()
    {
        return [
            ['int', 0, 0],
            ['int', 0, 5],
            ['int', 0, INF],
            ['int', -INF, INF],
            ['int', -INF, 0],
            ['int', -INF, 10],
            ['integer', 0, 0],
            ['integer', 0, 5],
            ['integer', 0, INF],
            ['integer', -INF, INF],
            ['integer', -INF, 0],
            ['integer', -INF, 10],
            ['float', 0, 0],
            ['float', 0, 5],
            ['float', 0, INF],
            ['float', -INF, INF],
            ['float', -INF, 0],
            ['float', -INF, 10],
            ['float', 2.5, 10.7]
        ];
    }

    /**
     * Test 'process' method
     *
     * @dataProvider constructProvider
     */
    public function testConstruct($type, $min, $max)
    {
        $tag = new NumberTag('foo', $type, $min, $max);

        $this->assertSame('foo', $tag->getName());
        $this->assertAttributeSame($type, 'type', $tag);
        $this->assertAttributeSame($min, 'min', $tag);
        $this->assertAttributeSame($max, 'max', $tag);
    }

    /**
     * Provide data for testing 'construct' method, in case when exception should be thrown
     *
     * @return array
     */
    public function constructExceptionProvider()
    {
        return [
            ['string', 0, 1, PhpdocException::class, 'NumberTag should be of type int or float, string given'],
            ['int', '0', 1, TypeError::class, 'Expected int or float, string given'],
            ['int', 0, [], TypeError::class, 'Expected int or float, array given'],
            ['float', 3, 1, PhpdocException::class, 'Min value (given 3) should be less than max (given 1)'],
        ];
    }

    /**
     * Test 'construct' method, if exception should be thrown
     *
     * @dataProvider constructExceptionProvider
     */
    public function testConstructException($type, $min, $max, $exceptionClass, $exceptionMessage)
    {
        $this->expectException($exceptionClass);
        $this->expectExceptionMessage($exceptionMessage);

        $tag = new NumberTag('foo', $type, $min, $max);
    }

    /**
     * Provide data for testing 'process' method
     *
     * @return array
     */
    public function processProvider()
    {
        return [
            ['int', '2 is a big number', ['some' => 'value', 'foo' => 2]],
            ['int', '2.53 is a big number', ['some' => 'value', 'foo' => 2]],
            ['float', '2.53 is a big number', ['some' => 'value', 'foo' => 2.53]],
            ['float', '+2.53 is a big number', ['some' => 'value', 'foo' => 2.53]],
            ['float', '-2.53 is a big number', ['some' => 'value', 'foo' => -2.53]],
        ];
    }

    /**
     * Test 'process' method
     *
     * @dataProvider processProvider
     */
    public function testProcess($type, $value, $expected)
    {
        $tag = new NumberTag('foo', $type, -10);
        $result = $tag->process(['some' => 'value'], $value);

        $this->assertSame($expected, $result);
    }

    /**
     * Provide data for testing 'process' method, if exception should be thrown
     *
     * @return array
     */
    public function processExceptionProvider()
    {
        return [
            ['int', 0, INF, '"2" is a big number', "Failed to parse '@foo \"2\"': not a number"],
            ['int', 0, INF, '2-and-half is a big number', "Failed to parse '@foo 2-and-half': not a number"],
            ['int', 0, INF, 'two is a big number', "Failed to parse '@foo two': not a number"],
            ['int', 0, 3, '4 is a big number', "Parsed value 4 should be less then max value 3"],
            ['int', 0, 3, '-1 is a big number', "Parsed value -1 should be greater then min value 0"],
        ];
    }

    /**
     * Test 'process' method, if exception should be thrown
     *
     * @dataProvider processExceptionProvider
     */
    public function testProcessException($type, $min, $max, $value, $exceptionMessage)
    {
        $this->expectException(PhpdocException::class);
        $this->expectExceptionMessage($exceptionMessage);

        $tag = new NumberTag('foo', $type, $min, $max);
        $tag->process(['some' => 'value'], $value);
    }
}
