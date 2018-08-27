<?php

namespace Jasny\Annotations\Tests\Tag;

use Jasny\Annotations\Tag\AbstractTag;
use Jasny\Annotations\Tests\AbstractTagChildTest;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\Annotations\Tag\AbstractTag
 */
class AbstractTagTest extends TestCase
{
    /**
     * Test 'getName' method
     */
    public function testGetName()
    {
        $tag = new AbstractTagChildTest('foo_name');
        $result = $tag->getName();

        $this->assertSame('foo_name', $result);
    }
}
