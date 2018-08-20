<?php

declare(strict_types=1);

namespace Jasny\Annotations\Tag;

/**
 * The complete description following the tag is the annotation value.
 */
class DescriptionTag extends AbstractTag
{
    /**
     * Process an annotation
     *
     * @param array $annotations
     * @param string $value
     */
    public function process(array &$annotations, string $value): void
    {
        $annotations[$this->getName()] = $value;
    }
}
