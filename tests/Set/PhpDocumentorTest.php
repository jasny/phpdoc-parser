<?php

namespace Jasny\PhpdocParser\Tests\Set;

use Jasny\PhpdocParser\TagSet;
use Jasny\PhpdocParser\TagInterface;
use Jasny\PhpdocParser\Set\PhpDocumentor;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\PhpdocParser\Set\PhpDocumentor
 */
class PhpDocumentorTest extends TestCase
{
    /**
     * Test 'tags' method
     */
    public function testTags()
    {
        $tags = PhpDocumentor::tags();

        $this->assertInstanceOf(TagSet::class, $tags);
    }
}
