<?php

declare(strict_types=1);

namespace Jasny\PhpdocParser\Tag;

use Jasny\PhpdocParser\Tag\AbstractTag;

/**
 * Tag is a flag, value is ignored.
 */
class FlagTag extends AbstractTag
{
    /**
     * Process a notation.
     *
     * @param array $notations
     * @param string $value
     * @return array
     */
    public function process(array $notations, string $value): array
    {
        $notations[$this->name] = true;

        return $notations;
    }
}
