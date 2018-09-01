<?php

namespace Jasny\Annotations\Tests;

use Jasny\Annotations\TagSet;
use Jasny\Annotations\TagInterface;
use Jasny\Annotations\Set\PhpDocumentor;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\Annotations\Set\PhpDocumentor
 */
class PhpDocumentorTest extends TestCase
{
    use \Jasny\TestHelper;

    /**
     * Test 'tags' method
     */
    public function testTags()
    {
        $tags = PhpDocumentor::tags();

        $this->assertInstanceOf(TagSet::class, $tags);
    }
}
