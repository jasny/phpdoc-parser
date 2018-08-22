<?php

declare(strict_types=1);

namespace Jasny\Annotations\Tag;

use Jasny\Annotations\AnnotationException;

use function Jasny\str_starts_with;

/**
 * Tag value represents an associative array
 */
class MapTag extends AbstractTag
{
    /**
     * @var bool
     */
    protected $assoc;

    /**
     * @var string
     * @options 'string', 'int', 'float'
     */
    protected $type;


    /**
     * Class constructor.
     *
     * @param string $name
     * @param bool   $assoc  Parse associative array syntax
     * @param string $type   ('string', 'int', 'float')
     */
    public function __construct(string $name, bool $assoc = false, string $type = 'string')
    {
        if (!in_array($type, ['string', 'int', 'float'])) {
            throw new \InvalidArgumentException("Invalid type '$type");
        }

        parent::__construct($name);

        $this->assoc = $assoc;
        $this->type = $type;
    }

    /**
     * Get the value type
     *
     * @return string  ('string', 'int', 'float')
     */
    public function getType(): string
    {
        return $this->type;
    }


    /**
     * Process the annotation
     *
     * @param array  $annotations
     * @param string $value
     * @return array
     */
    public function process(array $annotations, string $value): array
    {
        if ($value === '') {
            $annotations[$this->name] = [];

            return $annotations;
        }

        // Strip parentheses
        if (str_starts_with($value, '(')) {
            $value = preg_replace('/^\(((?:"(?:[^"]++|\\\\.)*"|(?:^|,)\s*\'(?:[^\']++|\\\\.)*\'|[^\)]+)*)\).*$/', '$1', $value);
        }

        $regexp = $this->type === 'string'
            ? '/(?<=^|,)\s*(?:\'(?:[^\']++|\\\\.)*\'|(?:"(?:[^"]++|\\\\.)*"|[^,\'"]+|[\'"])+)/'
            : '/(?<=^|,)\s*[+\\-]?\d+' . ($this->type === 'float' ? '(?:\.\d+)?' : '') . '\s*(?=,|$)/';

        if (!preg_match_all($regexp, $value, $matches, PREG_PATTERN_ORDER)) {
            throw new AnnotationException("Failed to parse '@{$this->name} {$value}': invalid syntax");
        }

        $annotations[$this->name] = $this->assoc
            ? $this->toAssoc($matches[0])
            : $this->toArray($matches[0]);

        return $annotations;
    }

    /**
     * Process matched items
     *
     * @param array $items
     * @return array
     */
    protected function toArray(array $items): array
    {
        foreach ($items as &$item) {
            $item = $this->type === 'string'
                ? preg_replace('/^\s*(["\']?)(.+)\1\s*$/', '$2', trim($item))
                : $item;

            settype($item, $this->type);
        }

        return $items;
    }

    /**
     * Process matched items as associative array
     *
     * @param array $items
     * @return array
     */
    protected function toAssoc(array $items): array
    {
        $result = [];

        foreach ($items as $item) {
            $regex = '/^(?:\s*(["\']?)(?<key>.+?)\1\s*=)?\s*(["\']?)(?<value>.+?)\3\s*$/';
            $matched = preg_match($regex, $item, $matches);

            $value = $matched ? $matches['value'] : $item;
            settype($value, $this->type);

            if (isset($matches['key'])) {
                $result[$matches['key']] = $item;
            } else {
                $result[] = $item;
            }
        }

        return $result;
    }
}
