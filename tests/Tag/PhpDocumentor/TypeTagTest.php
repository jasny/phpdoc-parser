<?php

namespace Jasny\PhpdocParser\Tests\Tag\PhpDocumentor;

use Jasny\PhpdocParser\Tag\PhpDocumentor\TypeTag;
use Jasny\PhpdocParser\PhpdocException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\PhpdocParser\Tag\PhpDocumentor\TypeTag
 * @covers \Jasny\PhpdocParser\Tag\AbstractTag
 */
class TypeTagTest extends TestCase
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
                'FooType',
                null,
                ['some' => 'value', 'foo' => ['type' => 'FooType']]
            ],
            [
                'FooType Some description here',
                null,
                ['some' => 'value', 'foo' => ['type' => 'FooType', 'description' => 'Some description here']]
            ],
            [
                'Bar\\Foo\\Type',
                null,
                ['some' => 'value', 'foo' => ['type' => 'Bar\\Foo\\Type']]
            ],
            [
                'Bar\\Foo\\Type Some description here',
                null,
                ['some' => 'value', 'foo' => ['type' => 'Bar\\Foo\\Type', 'description' => 'Some description here']]
            ],
            [
                'FooType Some description here',
                function($class) {
                    return 'Bar\\' . $class;
                },
                ['some' => 'value', 'foo' => ['type' => 'Bar\\FooType', 'description' => 'Some description here']]
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
        $tag = new TypeTag('foo', $fqsenConvertor);
        $result = $tag->process(['some' => 'value'], $value);

        $this->assertSame($expected, $result);
    }

    /**
     * Test 'process' method, if exception should be thrown
     */
    public function testProcessEmptyValue()
    {
        $tag = new TypeTag('foo');
        
        $this->expectException(PhpdocException::class);
        $this->expectExceptionMessageMatches("/Failed to parse '@foo': tag value should not be empty/");
    
        $result = $tag->process(['some' => 'value'], '');
    }
}
