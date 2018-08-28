<?php

namespace Jasny\Annotations\Tests\Tag;

use Jasny\Annotations\Tag\DescriptionTag;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\Annotations\Tag\DescriptionTag
 */
class DescriptionTagTest extends TestCase
{
    use \Jasny\TestHelper;

    /**
     * Test 'process' method
     */
    public function testProcess()
    {
        $tag = $this->createPartialMock(DescriptionTag::class, []);
        $this->setPrivateProperty($tag, 'name', 'foo');

        $result = $tag->process(['some' => 'value'], 'bar');

        $this->assertSame(['some' => 'value', 'foo' => 'bar'], $result);
    }
}
