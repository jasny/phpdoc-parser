<?php

declare(strict_types=1);

namespace Jasny\PhpdocParser\Tag;

use Jasny\PhpdocParser\Tag\AbstractTag;

/**
 * General doc-block summery and description
 */
class Summery extends AbstractTag
{
    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->name = 'summery';
    }

    /**
     * Process a notation.
     *
     * @param array $notations
     * @param string $value
     * @return array
     */
    public function process(array $notations, string $value): array
    {
        preg_match_all('/^\s*(?:(?:\/\*)?\*\s*)?([^@\s\/*].*?)\r?$/m', $value, $matches, PREG_PATTERN_ORDER);

        if (!$matches || empty($matches[1])) {
            return $notations;
        }

        $matches = $matches[1];

        $notations['summery'] = reset($matches);
        $notations['description'] = implode("\n", $matches);

        return $notations;
    }
}
