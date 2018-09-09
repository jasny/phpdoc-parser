<?php

declare(strict_types=1);

namespace Jasny\PhpdocParser\Tag;

use Jasny\PhpdocParser\TagInterface;

/**
 * Interface for tags that implement the Proxy pattern
 */
interface ProxyTagInterface
{

    /**
     * Get the wrapped tag.
     *
     * @return TagInterface
     */
    public function getTag(): TagInterface;
}
