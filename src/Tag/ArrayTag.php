<?php

declare(strict_types=1);

namespace Jasny\Annotations\Tag;

/**
 * Tag value represents an array
 */
class ArrayTag extends AbstractArrayTag
{
    /**
     * Split value into items.
     *
     * @param string $value
     * @return array
     */
    protected function splitValue(string $value): array
    {
        $regexp = '/(?<=^|,)\s*(?:\'(?:[^\']++|\\\\.)*\'|(?:"(?:[^"]++|\\\\.)*"|[^,\'"]+|[\'"])+)/';
        preg_match_all($regexp, $value, $matches, PREG_PATTERN_ORDER); // regex can't fail

        return $matches[0];
    }
}
