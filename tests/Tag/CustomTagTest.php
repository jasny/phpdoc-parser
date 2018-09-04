<?php

namespace Jasny\Annotations\Tests\Tag;

use Jasny\Annotations\Tag\CustomTag;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\Annotations\Tag\CustomTag
 */
class CustomTagTest extends TestCase
{
    /**
     * Provide data for testing 'process' method
     *
     * @return array
     */
    public function processProvider()
    {
        $process = function(array $annotations, $value) {
            return ['foo_name' => $value . '_changed'];
        };

        return [
            [$process, ['foo_name' => 'some_tag_value_changed']]
        ];
    }

    /**
     * Test 'process' method
     *
     * @dataProvider processProvider
     */
    public function testProcess($process, $expected)
    {
        $tag = new CustomTag('foo_name', $process);
        $result = $tag->process([], 'some_tag_value');

        $this->assertSame($expected, $result);
    }

    /**
     * Mock function for processing tag value
     *
     * @param string $value
     * @return string
     */
    public function customProcess($value)
    {
        return ['foo_name' => $value . '_changed'];
    }

    /**
     * Test 'getName' method
     */
    public function testGetName()
    {
        $tag = new CustomTag('foo_name', function() {});
        $result = $tag->getName();

        $this->assertSame('foo_name', $result);
    }
}
