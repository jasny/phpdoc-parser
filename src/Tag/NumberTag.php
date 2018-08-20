<?php

declare(strict_types=1);

namespace Jasny\Annotations\Tag;

use Jasny\Annotations\Tag\AbstractTag;
use Jasny\Annotations\AnnotationException;

use function Jasny\str_contains;

/**
 * Only use the first word after the tag, ignoring the rest
 */
class NumberTag extends AbstractTag
{
    /**
     * Process an annotation.
     *
     * @param array  $annotations
     * @param string $value
     * @return void
     */
    public function process(array &$annotations, string $value): void
    {
        [$word] = explode(' ', $value, 2);

        if (!is_numeric($word)) {
            throw new AnnotationException("Failed to parse '@{$this->name} $word': not a number");
        }

        $annotations[$this->name] = $word + 0;
    }
}
