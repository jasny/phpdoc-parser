<?php

namespace Jasny\Annotations\Tests\Tag;

use Jasny\TestHelper;
use PHPUnit\Framework\TestCase;
use Jasny\Annotations\Tag\MapTag;

/**
 * @covers \Jasny\Annotations\Tag\MapTag
 * @covers \Jasny\Annotations\Tag\AbstractArrayTag
 */
class MapTagTest extends TestCase
{
    use TestHelper;

    public function testGetName()
    {
        $tag = new MapTag('foo');
        $this->assertEquals('foo', $tag->getName());
    }

    public function testGetTypeDefault()
    {
        $tag = new MapTag('foo');
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
        $tag = new MapTag('foo', $type);
        $this->assertEquals($type, $tag->getType());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid type 'ton'
     */
    public function testGetTypeInvalid()
    {
        $tag = new MapTag('foo', 'ton');
        $tag->getType();
    }


    public function testProcess()
    {
        $tag = new MapTag('foo');

        $result = $tag->process(['bar' => 1], 'red=good, green = better, blue =  best');
        $this->assertEquals(['bar' => 1, 'foo' => ['red' => 'good', 'green' => 'better', 'blue' => 'best']], $result);
    }

    public function testProcessParenthesis()
    {
        $tag = new MapTag('foo');

        $result = $tag->process([], '(red=good, green=better, blue=best) to be ignored');
        $this->assertEquals(['foo' => ['red' => 'good', 'green' => 'better', 'blue' => 'best']], $result);
    }

    public function testProcessEmpy()
    {
        $tag = new MapTag('foo');

        $result = $tag->process([], '');
        $this->assertEquals(['foo' => []], $result);
    }

    public function testProcessQuoted()
    {
        $value = 'one="hello, world", "t w o"=greetings, \'three\'=\'bye, bye\', four = this is "also = quoted",'
            . 'five=o\'reilly, six=o\'kay';

        $tag = new MapTag('foo');

        $result = $tag->process([], $value);

        $expected = ['one' => 'hello, world', 't w o' => 'greetings', 'three' => 'bye, bye',
            'four' => 'this is "also = quoted"', 'five' => 'o\'reilly', 'six' => 'o\'kay'];
        $this->assertEquals(['foo' => $expected], $result);
    }

    public function testProcessQuotedParenthesis()
    {
        $value = '(iam="not (here)", one = two)';
        $tag = new MapTag('foo');

        $result = $tag->process([], $value);
        $this->assertEquals(['foo' => ['iam' => 'not (here)', 'one' => 'two']], $result);
    }

    public function testProcessSkip()
    {
        $tag = new MapTag('foo');

        $result = $tag->process([], 'start=hi,middle=,next = ,end=bye');
        $this->assertSame(['foo' => ['start' => 'hi', 'middle' => '', 'next' => '', 'end' => 'bye']], $result);
    }

    public function testProcessString()
    {
        $tag = new MapTag('foo', 'string');

        $result = $tag->process([], '1=hi, 2=42, 3=bye');
        $this->assertSame(['foo' => [1 => 'hi', 2 => '42', 3 => 'bye']], $result);
    }

    public function testProcessAssocInt()
    {
        $tag = new MapTag('foo', 'int');

        $result = $tag->process([], 'red = 66, green = 229, blue = 244');
        $this->assertEquals(['foo' => ['red' => 66, 'green' => 229, 'blue' => 244]], $result);
    }

    public function testProcessAssocFloat()
    {
        $tag = new MapTag('foo', 'float');

        $result = $tag->process([], 'a = 3.14, b = 7, c = 10e4, d = 1.41429, e = -1.2');
        $this->assertEquals(['foo' => ['a' => 3.14, 'b' => 7.0, 'c' => 10e4, 'd' => 1.41429, 'e' => -1.2]], $result);
    }

    /**
     * @expectedException \Jasny\Annotations\AnnotationException
     * @expectedExceptionMessage Failed to parse '@foo red = 66, green, blue = 244': no key for value 'green'
     */
    public function testProcessInvalidNoKey()
    {
        $tag = new MapTag('foo');
        $tag->process([], 'red = 66, green, blue = 244');
    }

    /**
     * @expectedException \Jasny\Annotations\AnnotationException
     * @expectedExceptionMessage Failed to parse '@foo red = 66, =229, blue = 244': no key for value '229'
     */
    public function testProcessInvalidBlankKey()
    {
        $tag = new MapTag('foo');
        $tag->process([], 'red = 66, =229, blue = 244');
    }

    /**
     * @expectedException \Jasny\Annotations\AnnotationException
     * @expectedExceptionMessage Failed to parse '@foo a = 10, b = 33.2, c = 20': invalid value '33.2'
     */
    public function testProcessInvalidInt()
    {
        $tag = new MapTag('foo', 'int');
        $tag->process([], 'a = 10, b = 33.2, c = 20');
    }

    /**
     * @expectedException \Jasny\Annotations\AnnotationException
     * @expectedExceptionMessage Failed to parse '@foo a = 10, b = 33.., c = 20': invalid value '33..'
     */
    public function testProcessInvalidFloat()
    {
        $tag = new MapTag('foo', 'float');
        $tag->process([], 'a = 10, b = 33.., c = 20');
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testProcessInvalidType()
    {
        $tag = new MapTag('foo');
        $this->setPrivateProperty($tag, 'type', 'abc');

        $tag->process([], 'a = 10');
    }}
