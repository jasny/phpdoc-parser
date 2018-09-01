<?php

namespace Jasny\Annotations\Tests;

use Jasny\Annotations\Tag\AbstractArrayTag;

/**
 * Implementation of abstract class AbstractArrayTag
 */
class AbstractArrayTagChildTest extends AbstractArrayTag
{

    /**
     * Split value into items.
     *
     * @param string $value
     * @return array
     */
    protected function splitValue(string $value): array
    {
        return explode(', ', $value);
    }
}
