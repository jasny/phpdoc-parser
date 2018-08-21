<?php

declare(strict_types=1);

namespace Jasny\Annotations\Tag\PhpDocumentor;

use Jasny\Annotations\Tag\AbstractTag;
use Jasny\Annotations\AnnotationException;

/**
 * Custom logic for PhpDocumentor 'example' tag
 */
class ExampleTag extends AbstractTag
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
        $regexp = '/^(?:(?<location>[^"]\S*)|"(?<location>[^"]+)")'
            . '(?:\s*(?<start_line>\d+)(?:\s*(?<number_of_lines>\d+))?)?/J';

        if (!preg_match($regexp, $value, $matches)) {
            throw new AnnotationException("Failed to parse '@{$this->name} $value': invalid syntax");
        }

        $matches['start_line'] = isset($matches['start_line']) ? (int)$matches['start_line'] : null;
        $matches['number_of_lines'] = isset($matches['number_of_lines']) ? (int)$matches['number_of_lines'] : null;

        $annotations['example'] = $matches;

        return $annotations;
    }
}
