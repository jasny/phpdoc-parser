<?php

namespace Jasny\Annotations\Tests\Tag;

use Jasny\TestHelper;
use PHPUnit\Framework\TestCase;
use Jasny\Annotations\Tag\ArrayTag;

/**
 * @covers \Jasny\Annotations\Tag\ArrayTag
 * @covers \Jasny\Annotations\Tag\AbstractArrayTag
 * @covers \Jasny\Annotations\Tag\AbstractTag
 */
class ArrayTagTest extends TestCase
{
    use TestHelper;

    public function testConstruct()
    {
        $tag = new ArrayTag('foo');
        $this->assertEquals('foo', $tag->getName());
    }

    public function testGetTypeDefault()
    {
        $tag = new ArrayTag('foo');
        $this->assertEquals('string', $tag->getType());
    }

    public function typeProvider()
    {
        return [
            ['string'],
            ['int'],
            ['float']
        ];
    }

    /**
     * @dataProvider typeProvider
     */
    public function testGetType(string $type)
    {
        $tag = new ArrayTag('foo', $type);
        $this->assertEquals($type, $tag->getType());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid type 'ton'
     */
    public function testGetTypeInvalid()
    {
        $tag = new ArrayTag('foo', 'ton');
        $tag->getType();
    }

    public function testProcess()
    {
        $tag = new ArrayTag('foo');

        $result = $tag->process(['bar' => true], 'red, green, blue');
        $this->assertEquals(['bar' => true, 'foo' => ['red', 'green', 'blue']], $result);
    }

    public function testProcessParenthesis()
    {
        $tag = new ArrayTag('foo');

        $result = $tag->process(['bar' => true], '(red, green, blue) to be ignored');
        $this->assertEquals(['bar' => true, 'foo' => ['red', 'green', 'blue']], $result);
    }

    public function testProcessEmpy()
    {
        $tag = new ArrayTag('foo');

        $result = $tag->process([], '');
        $this->assertEquals(['foo' => []], $result);
    }

    public function testProcessQuoted()
    {
        $value = '"hello, world", greetings, \'bye, bye\', this is "also, quoted", o\'reilly, o\'kay';
        $tag = new ArrayTag('foo');

        $result = $tag->process([], $value);

        $expected = ['hello, world', 'greetings', 'bye, bye', 'this is "also, quoted"', 'o\'reilly', 'o\'kay'];
        $this->assertEquals(['foo' => $expected], $result);
    }

    public function testProcessQuotedParenthesis()
    {
        $value = '("not (here)", one = two, greetings)';
        $tag = new ArrayTag('foo');

        $result = $tag->process([], $value);
        $this->assertEquals(['foo' => ['not (here)', 'one = two', 'greetings']], $result);
    }

    public function testProcessSkip()
    {
        $tag = new ArrayTag('foo');

        $result = $tag->process([], 'hi, , bye');
        $this->assertSame(['foo' => ['hi', '', 'bye']], $result);
    }

    public function testProcessString()
    {
        $tag = new ArrayTag('foo', 'string');

        $result = $tag->process([], 'hi, 42, bye');
        $this->assertSame(['foo' => ['hi', '42', 'bye']], $result);
    }

    public function testProcessInt()
    {
        $tag = new ArrayTag('foo', 'int');

        $result = $tag->process([], '3, 5, 11, 17, 31, -1, +2');
        $this->assertSame(['foo' => [3, 5, 11, 17, 31, -1, 2]], $result);
    }

    public function testProcessFloat()
    {
        $tag = new ArrayTag('foo', 'float');

        $result = $tag->process([], '3.14, 7, 10e4, 1.41429, -1.2');
        $this->assertSame(['foo' => [3.14, 7.0, 10e4, 1.41429, -1.2]], $result);
    }

    /**
     * @expectedException \Jasny\Annotations\AnnotationException
     * @expectedExceptionMessage Failed to parse '@foo 10, 33.2, 20': invalid value '33.2'
     */
    public function testProcessInvalidInt()
    {
        $tag = new ArrayTag('foo', 'int');
        $tag->process([], '10, 33.2, 20');
    }

    /**
     * @expectedException \Jasny\Annotations\AnnotationException
     * @expectedExceptionMessage Failed to parse '@foo 10, 33.., 20': invalid value '33..'
     */
    public function testProcessInvalidFloat()
    {
        $tag = new ArrayTag('foo', 'float');
        $tag->process([], '10, 33.., 20');
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testProcessInvalidType()
    {
        $tag = new ArrayTag('foo');
        $this->setPrivateProperty($tag, 'type', 'abc');

        $tag->process([], 'a');
    }
}
