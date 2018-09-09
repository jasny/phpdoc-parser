<?php

namespace Jasny\PhpdocParser\Tests\Tag\PhpDocumentor;

use Jasny\PhpdocParser\Tag\PhpDocumentor\MethodTag;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\PhpdocParser\Tag\PhpDocumentor\MethodTag
 * @covers \Jasny\PhpdocParser\Tag\AbstractTag
 */
class MethodTagTest extends TestCase
{
    /**
     * Provide data for testing 'process' method
     *
     * @return array
     */
    public function processProvider()
    {
        return [
            [
                'string someMethod($one, Foo $two, int $three = 12, string $four = "bar", string $five = \'zoo\', array $six = ["test"]) Some method description here',
                null,
                [
                    'some' => 'value',
                    'foo' => [
                        'return_type' => 'string',
                        'name' => 'someMethod',
                        'params' => [
                            'one' => [
                                'name' => 'one'
                            ],
                            'two' => [
                                'type' => 'Foo',
                                'name' => 'two',
                            ],
                            'three' => [
                                'type' => 'int',
                                'name' => 'three',
                                'default' => '12'
                            ],
                            'four' => [
                                'type' => 'string',
                                'name' => 'four',
                                'default' => 'bar'
                            ],
                            'five' => [
                                'type' => 'string',
                                'name' => 'five',
                                'default' => 'zoo'
                            ],
                            'six' => [
                                'type' => 'array',
                                'name' => 'six',
                                'default' => '["test"]'
                            ],
                        ],
                        'description' => 'Some method description here'
                    ]
                ]
            ],
            [
                'Zoo someMethod($one, Foo $two, Bar\\Bars $three = null) Some method description here',
                function($class) {
                    return 'any_namespace\\' . $class;
                },
                [
                    'some' => 'value',
                    'foo' => [
                        'return_type' => 'any_namespace\\Zoo',
                        'name' => 'someMethod',
                        'params' => [
                            'one' => [
                                'name' => 'one'
                            ],
                            'two' => [
                                'type' => 'any_namespace\\Foo',
                                'name' => 'two',
                            ],
                            'three' => [
                                'type' => 'any_namespace\\Bar\\Bars',
                                'name' => 'three',
                                'default' => 'null'
                            ]
                        ],
                        'description' => 'Some method description here'
                    ]
                ]
            ],
        ];
    }

    /**
     * Test 'process' method
     *
     * @dataProvider processProvider
     */
    public function testProcess($value, $fqsenConvertor, $expected)
    {
        $tag = new MethodTag('foo', $fqsenConvertor);
        $result = $tag->process(['some' => 'value'], $value);

        $this->assertSame($expected, $result);
    }

    /**
     * Provide data for testing 'process' method, in case when exception should be thrown
     *
     * @return array
     */
    public function processExceptionProvider()
    {
        return [
            ['Zoo $someMethod($one, Foo $two, Bar\\Bars $three = null) Some method description here'],
            ['Zoo someMethod(one, Foo $two, Bar\\Bars $three = test) Some method description here'],
        ];
    }

    /**
     * Test 'process' method, if exception should be thrown
     *
     * @dataProvider processExceptionProvider
     * @expectedException Jasny\PhpdocParser\PhpdocException
     * @expectedExceptionMessageRegExp /Failed to parse '@foo .*?': invalid syntax/
     */
    public function testProcessException($value)
    {
        $tag = new MethodTag('foo');
        $result = $tag->process(['some' => 'value'], $value);
    }
}
