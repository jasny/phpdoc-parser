<?php

declare(strict_types=1);

namespace Jasny\Annotations\Tag;

use Jasny\Annotations\TagInterface;

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