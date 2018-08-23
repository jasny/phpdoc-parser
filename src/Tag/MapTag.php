<?php

declare(strict_types=1);

namespace Jasny\Annotations\Tag;

use Jasny\Annotations\AnnotationException;

use function Jasny\str_starts_with;

/**
 * Tag value represents a map (aka associative array).
 */
class MapTag extends AbstractArrayTag
{
    /**
     * Split value into items.
     *
     * @param string $value
     * @return array
     */
    protected function splitValue(string $value): array
    {
        $regexpKey = '(?:\'(?:[^\']++|\\\\.)*\'|"(?:[^"]++|\\\\.)*"|[^,\'"]+|[\'"])+)';
        $regexpValue = '(?:\'(?:[^\']++|\\\\.)*\'|(?:"(?:[^"]++|\\\\.)*"|[^,\'"]+|[\'"])+)';
        $regexp = '/(?<=^|,)(?:(?<key>' . $regexpKey . ')\s*=\s*)?(?<value>' . $regexpValue. ')\s*/';

        preg_match_all($regexp, $value, $matches, PREG_PATTERN_ORDER); // regex can't fail

        foreach ($matches[1] as $key) {
            if ($key === null || $key === '') {
                throw new AnnotationException("Failed to parse '@{$this->name} {$value}': invalid syntax");
            }
        }

        return array_combine($matches[0], $matches[1]);
    }
}
