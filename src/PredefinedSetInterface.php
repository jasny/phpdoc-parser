<?php

declare(strict_types=1);

namespace Jasny\Annotations;

use Jasny\Annotations\TagSet;

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
