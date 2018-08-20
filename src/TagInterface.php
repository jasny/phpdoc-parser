<?php

declare(strict_types=1);

namespace Jasny\Annotations;

interface TagInterface
{
    /**
     * Get the tag name
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Process an annotation.
     *
     * @param array  $annotations
     * @param string $value
     * @return array
     */
    public function process(array $annotations, string $value): array;
}
