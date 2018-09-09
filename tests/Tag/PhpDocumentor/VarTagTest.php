<?php

namespace Jasny\PhpdocParser\Tests\Tag\PhpDocumentor;

use Jasny\PhpdocParser\Tag\PhpDocumentor\VarTag;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\PhpdocParser\Tag\PhpDocumentor\VarTag
 * @covers \Jasny\PhpdocParser\Tag\AbstractTag
 */
class VarTagTest extends TestCase
{
    /**
     * Test 'getAdditionalProperties' method
     */
    public function testGetAdditionalProperties()
    {
        $additional = ['bar' => 'baz'];
        $tag = new VarTag('foo', null, $additional);
        $result = $tag->getAdditionalProperties();

        $this->assertSame($additional, $result);
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
                '$amount Some description here',
                null,
                [],
                [
                    'some' => 'value',
                    'foo' => [
                        'name' => 'amount'
                    ]
                ]
            ],
            [
                'int $amount "some id"',
                null,
                [],
                [
                    'some' => 'value',
                    'foo' => [
                        'type' => 'int',
                        'name' => 'amount',
                        'id' => 'some id'
                    ]
                ]
            ],
            [
                'int|string|Foo\\Bar|null $amount "some id"',
                null,
                ['any' => 'adds', 'little' => 'rats'],
                [
                    'some' => 'value',
                    'foo' => [
                        'type' => 'int|string|Foo\\Bar|null',
                        'name' => 'amount',
                        'id' => 'some id',
                        'any' => 'adds',
                        'little' => 'rats'
                    ]
                ]
            ],
            [
                'Foo $amount "some id" Some description here',
                function($class) {
                    return 'some_namespace\\' . $class;
                },
                ['any' => 'adds', 'little' => 'rats'],
                [
                    'some' => 'value',
                    'foo' => [
                        'type' => 'some_namespace\\Foo',
                        'name' => 'amount',
                        'id' => 'some id',
                        'any' => 'adds',
                        'little' => 'rats'
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
    public function testProcess($value, $fqsenConvertor, $additional, $expected)
    {
        $tag = new VarTag('foo', $fqsenConvertor, $additional);
        $result = $tag->process(['some' => 'value'], $value);

        $this->assertSame($expected, $result);
    }
}
