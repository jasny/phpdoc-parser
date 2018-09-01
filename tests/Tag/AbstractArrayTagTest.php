<?php

namespace Jasny\Annotations\Tests\Tag;

use Jasny\Annotations\Tag\AbstractArrayTag;
use Jasny\Annotations\Tests\AbstractArrayTagChildTest;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\Annotations\Tag\AbstractArrayTag
 */
class AbstractArrayTagTest extends TestCase
{
    use \Jasny\TestHelper;

    /**
     * Provide data for testing 'getType' method
     *
     * @return array
     */
    public function getTypeProvider()
    {
        return [
            ['string'],
            ['int'],
            ['float']
        ];
    }

    /**
     * Test 'getType' method
     *
     * @dataProvider getTypeProvider
     */
    public function testGetType($type)
    {
        $tag = new AbstractArrayTagChildTest('foo', $type);
        $result = $tag->getType();

        $this->assertSame($type, $result);
    }

    /**
     * Provide data for testing 'construct' method, if exception should be thrown
     *
     * @return array
     */
    public function constructExceptionProvider()
    {
        return [
            [''],
            ['integer'],
            ['foo']
        ];
    }

    /**
     * Test 'construct' method, if exception should be thrown
     *
     * @dataProvider constructExceptionProvider
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessageRegExp /Invalid type '.*?'/
     */
    public function testConstructException($type)
    {
        $tag = new AbstractArrayTagChildTest('foo', $type);
    }

    /**
     * Provide data for testing 'process' method
     *
     * @return array
     */
    public function processProvider()
    {
        return [
            [
                '',
                'string',
                [
                    'some' => 'value',
                    'foo' => []
                ]
            ],
            [
                'bar, baz, rest',
                'string',
                [
                    'some' => 'value',
                    'foo' => ['bar', 'baz', 'rest']
                ]
            ],
            [
                '("bar", \'baz\', rest) and so on',
                'string',
                [
                    'some' => 'value',
                    'foo' => ['bar', 'baz', 'rest']
                ]
            ],
            [
                '(25, +28, -30, 0) and so on',
                'string',
                [
                    'some' => 'value',
                    'foo' => ['25', '+28', '-30', '0']
                ]
            ],
            [
                '(25, +28, -30, 0) and so on',
                'int',
                [
                    'some' => 'value',
                    'foo' => [25, 28, -30, 0]
                ]
            ],
            [
                '(25, 1.50, +28.6, -30.8, 0) and so on',
                'float',
                [
                    'some' => 'value',
                    'foo' => [25., 1.5, 28.6, -30.8, 0.]
                ]
            ]
        ];
    }

    /**
     * Test 'process' method
     *
     * @dataProvider processProvider
     */
    public function testProcess($value, $type, $expected)
    {
        $tag = new AbstractArrayTagChildTest('foo', $type);
        $result = $tag->process(['some' => 'value'], $value);

        $this->assertSame($expected, $result);
    }

    /**
     * Provide data for testing 'process' method, in case when excepion should be thrown
     *
     * @return array
     */
    public function processExceptionProvider()
    {
        return [
            [
                '25, -2, rest',
                'int',
            ],
            [
                '25, -2, rest',
                'float',
            ]
        ];
    }

    /**
     * Test 'process' method, in case when exception should be thrown
     *
     * @dataProvider processExceptionProvider
     * @expectedException Jasny\Annotations\AnnotationException
     * @expectedExceptionMessageRegExp /Failed to parse '@foo .*?': invalid syntax/
     */
    public function testProcessException($value, $type)
    {
        $tag = new AbstractArrayTagChildTest('foo', $type);
        $result = $tag->process(['some' => 'value'], $value);
    }

    /**
     * Test 'process' method, if in splited value keys have quotes
     */
    public function testProcessQuotes()
    {
        $value = '"another" -> meaning';
        $expected = [
            'some' => 'value',
            'foo' => ['another' => 'meaning']
        ];

        $tag = $this->createPartialMock(AbstractArrayTagChildTest::class, ['splitValue']);
        $tag->expects($this->once())->method('splitValue')->with($value)->willReturn(['"another"' => 'meaning']);
        $this->setPrivateProperty($tag, 'name', 'foo');
        $this->setPrivateProperty($tag, 'type', 'string');

        $result = $tag->process(['some' => 'value'], $value);

        $this->assertSame($expected, $result);
    }
}
