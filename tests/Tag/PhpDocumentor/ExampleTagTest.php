<?php

namespace Jasny\PhpdocParser\Tests\Tag\PhpDocumentor;

use Jasny\PhpdocParser\Tag\PhpDocumentor\ExampleTag;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\PhpdocParser\Tag\PhpDocumentor\ExampleTag
 * @covers \Jasny\PhpdocParser\Tag\AbstractTag
 */
class ExampleTagTest extends TestCase
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
                'some_dir/and_file.php',
                [
                    'some' => 'value',
                    'foo' => [
                        'location' => 'some_dir/and_file.php'
                    ]
                ]
            ],
            [
                'some_dir/and_file.php 47',
                [
                    'some' => 'value',
                    'foo' => [
                        'location' => 'some_dir/and_file.php',
                        'start_line' => 47
                    ]
                ]
            ],
            [
                'some_dir/and_file.php 47 39',
                [
                    'some' => 'value',
                    'foo' => [
                        'location' => 'some_dir/and_file.php',
                        'start_line' => 47,
                        'number_of_lines' => 39
                    ]
                ]
            ],
            [
                '"some dir/and file.php" 47 39',
                [
                    'some' => 'value',
                    'foo' => [
                        'location' => 'some dir/and file.php',
                        'start_line' => 47,
                        'number_of_lines' => 39
                    ]
                ]
            ],
            [
                '"some dir/and file.php" 47 39 And following description',
                [
                    'some' => 'value',
                    'foo' => [
                        'location' => 'some dir/and file.php',
                        'start_line' => 47,
                        'number_of_lines' => 39
                    ]
                ]
            ],
            [
                '"some dir/and file.php" And following description',
                [
                    'some' => 'value',
                    'foo' => [
                        'location' => 'some dir/and file.php'
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
    public function testProcess($value, $expected)
    {
        $tag = new ExampleTag('foo');
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
            [''],
            ['"some dir/and file.php'],
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
        $tag = new ExampleTag('foo');
        $result = $tag->process(['some' => 'value'], $value);
    }
}
