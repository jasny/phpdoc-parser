<?php

declare(strict_types=1);

namespace Jasny\Annotations\Tag\PhpDocumentor;

use Jasny\Annotations\Tag\AbstractTag;
use Jasny\Annotations\AnnotationException;
use function Jasny\array_only;

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
        $regexp = '/^(?<location>(?:[^"]\S*|"[^"]+"))(?:\s*(?<start_line>\d+)(?:\s*(?<number_of_lines>\d+))?)?/';

        if (!preg_match($regexp, $value, $matches)) {
            throw new AnnotationException("Failed to parse '@{$this->name} $value': invalid syntax");
        }

        $matches['location'] = trim($matches['location'], '"');

        if (isset($matches['start_line'])) {
            $matches['start_line'] = (int)$matches['start_line'];
        }

        if (isset($matches['number_of_lines'])) {
            $matches['number_of_lines'] = (int)$matches['number_of_lines'];
        }

        $matches = array_only($matches, ['location', 'start_line', 'number_of_lines']);

        $annotations[$this->name] = $matches;

        return $annotations;
    }
}
