<?php

declare(strict_types=1);

namespace Jasny\PhpdocParser\Tag\PhpDocumentor;

use Jasny\PhpdocParser\Tag\AbstractTag;
use Jasny\PhpdocParser\PhpdocException;
use function Jasny\array_only;

/**
 * Custom logic for PhpDocumentor 'example' tag
 */
class ExampleTag extends AbstractTag
{
    /**
     * Process a notation.
     *
     * @param array  $notations
     * @param string $value
     * @return array
     */
    public function process(array $notations, string $value): array
    {
        $regexp = '/^(?<location>(?:[^"]\S*|"[^"]+"))(?:\s*(?<start_line>\d+)(?:\s*(?<number_of_lines>\d+))?)?/';

        if (!preg_match($regexp, $value, $matches)) {
            throw new PhpdocException("Failed to parse '@{$this->name} $value': invalid syntax");
        }

        $matches['location'] = trim($matches['location'], '"');

        if (isset($matches['start_line'])) {
            $matches['start_line'] = (int)$matches['start_line'];
        }

        if (isset($matches['number_of_lines'])) {
            $matches['number_of_lines'] = (int)$matches['number_of_lines'];
        }

        $matches = array_only($matches, ['location', 'start_line', 'number_of_lines']);

        $notations[$this->name] = $matches;

        return $notations;
    }
}
