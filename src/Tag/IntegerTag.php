<?php

declare(strict_types=1);

namespace Jasny\Annotations\Tag;

use Jasny\Annotations\Tag\AbstractTag;
use Jasny\Annotations\AnnotationException;

use function Jasny\str_contains;

/**
 * Only use the first word after the tag, ignoring the rest
 */
class IntegerTag extends AbstractTag
{
    /**
     * Process an annotation.
     *
     * @param array  $annotations
     * @param string $value
     * @return array
     */
    public function process(array $annotations, string $value): array
    {
        [$word] = explode(' ', $value, 2);

        if (!preg_match('^[+\-]\d+$', $word)) {
            throw new AnnotationException("Failed to parse '@{$this->name} $word': not an integer");
        }

        $annotations[$this->name] = (int)$word;

        return $annotations;
    }
}
