<?php

namespace Jasny\Annotations\Tests\Tag;

use Jasny\Annotations\Tag\FlagTag;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\Annotations\Tag\FlagTag
 */
class FlagTagTest extends TestCase
{
    use \Jasny\TestHelper;

    /**
     * Test 'process' method
     */
    public function testProcess()
    {
        $tag = $this->createPartialMock(FlagTag::class, []);
        $this->setPrivateProperty($tag, 'name', 'foo');

        $result = $tag->process(['some' => 'value'], 'bar');

        $this->assertSame(['some' => 'value', 'foo' => true], $result);
    }
}
