<?php

declare(strict_types=1);

namespace Jasny\PhpdocParser;

use Jasny\PhpdocParser\TagSet;

/**
 * Interface for predefined sets
 */
interface PredefinedSetInterface
{
    /**
     * Get the tags
     *
     * @return TagSet
     */
    public static function tags(): TagSet;
}
