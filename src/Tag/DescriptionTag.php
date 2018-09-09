<?php

declare(strict_types=1);

namespace Jasny\PhpdocParser\Tag;

/**
 * The complete description following the tag is the notation value.
 */
class DescriptionTag extends AbstractTag
{
    /**
     * Process a notation
     *
     * @param array  $notations
     * @param string $value
     * @return array
     */
    public function process(array $notations, string $value): array
    {
        $notations[$this->name] = $value;

        return $notations;
    }
}
