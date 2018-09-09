<?php

declare(strict_types=1);

namespace Jasny\PhpdocParser;

interface TagInterface
{
    /**
     * Get the tag name
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Process a notation.
     *
     * @param array  $notations
     * @param string $value
     * @return array
     */
    public function process(array $notations, string $value): array;
}
