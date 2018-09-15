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
        $regexp = '/^(?<location>(?:[^"]\S*|"[^"]+"))(?:\s*(?<start_line>\d+)(?:\s*(?<number_of_lines>\d+))?)?(?:\s*(?<description>.+))?/';

        if (!preg_match($regexp, $value, $matches)) {
            throw new PhpdocException("Failed to parse '@{$this->name} $value': invalid syntax");
        }

        $this->normalizeValues($matches);
        $matches = array_only($matches, ['location', 'start_line', 'number_of_lines', 'description']);

        $notations[$this->name] = $matches;

        return $notations;
    }

    /**
     * Normalize parsed values
     *
     * @param array $values
     */
    protected function normalizeValues(array &$values): void
    {
        $values['location'] = trim($values['location'], '"');

        foreach (['start_line', 'number_of_lines'] as $name) {
            if (!isset($values[$name])) continue;

            if ($values[$name] === '') {
                unset($values[$name]);
            } else {
                $values[$name] = (int)$values[$name];
            }
        }
    }
}
