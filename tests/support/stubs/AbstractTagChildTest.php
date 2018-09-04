<?php

namespace Jasny\Annotations\Tests;

use Jasny\Annotations\Tag\AbstractTag;

/**
 * Implementation of abstract class AbstractTag
 */

class AbstractTagChildTest extends AbstractTag {

    /**
     * Process an annotation.
     *
     * @param array  $annotations
     * @param string $value
     * @return array
     */
    public function process(array $annotations, string $value): array
    {

    }
}
