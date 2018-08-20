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
     * @param array  $annotations
     * @param string $value
     * @return array
     */
    public function process(array $annotations, string $value): array
    {
        $annotations[$this->name] = $value;

        return $annotations;
    }
}
