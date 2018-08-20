<?php

declare(strict_types=1);

namespace Jasny\Annotations\Tag;

use Jasny\Annotations\Tag\AbstractTag;
use Jasny\Annotations\AnnotationException;

/**
 * Tag value represents an array
 */
class ArrayTag extends AbstractTag
{
    /**
     * @var bool
     */
    protected $assoc;


    /**
     * Class constructor.
     *
     * @param string $name
     * @param bool   $assoc  Parse associative array syntax
     */
    public function __construct(string $name, bool $assoc = false)
    {
        parent::__construct($name);

        $this->assoc = $assoc;
    }

    /**
     * Parse associative array syntax
     *
     * @return bool
     */
    protected function isAssoc(): bool
    {
        return $this->assoc;
    }


    /**
     * Process the annotation
     *
     * @param array $annotations
     * @param string $value
     * @return void
     */
    public function process(array &$annotations, string $value): void
    {
        // Strip parentheses
        $raw = preg_replace('/^s*\((.*)\)\s*$/', '$1', $value);

        $regexp = '/(?<=^|,)(?:"(?:[^"]+|\\\\.)*"|\'(?:[^\']+|\\\\.)*\'|[^,\'"]+|[\'"])+/';

        if (preg_match_all($regexp, $value, $matches, PREG_PATTERN_ORDER)) {
            throw new AnnotationException("Failed to parse '@{$this->name} {$value}': invalid syntax");
        }

        $annotations[$this->name] = $this->assoc
            ? $this->toAssoc($matches[0])
            : $this->toArray($matches[0]);
    }

    /**
     * Process matched items
     *
     * @param array $items
     * @return array
     */
    public function toArray(array $items): array
    {
        foreach ($items as &$item) {
            $item = preg_replace('/^\s*(["\']?)(.+)\1\s*$/', '$2', $item);
        }

        return $items;
    }

    /**
     * Process matched items as associative array
     *
     * @param array $items
     * @return array
     */
    public function toAssoc(array $items): array
    {
        $result = [];

        foreach ($items as $item) {
            preg_match('^(?:\s*(["\']?)(?<key>.+?)\1\s*=)?\s*(["\']?)(?<value>.+?)\3\s*$', $item, $matches);

            if (isset($matches['key'])) {
                $result[$matches['key']] = $matches['value'];
            } else {
                $result[] = $matches['value'];
            }
        }

        return $result;
    }
}
