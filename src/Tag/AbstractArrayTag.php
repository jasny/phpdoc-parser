<?php

declare(strict_types=1);

namespace Jasny\Annotations\Tag;

use Jasny\Annotations\AnnotationException;

use function Jasny\str_starts_with;

/**
 * Base class for array type tags
 */
abstract class AbstractArrayTag extends AbstractTag
{
    /**
     * @var string
     * @enum 'string', 'int', 'float'
     */
    protected $type;


    /**
     * Class constructor.
     *
     * @param string $name
     * @param bool   $assoc  Parse associative array syntax
     * @param string $type   ('string', 'int', 'float')
     */
    public function __construct(string $name, string $type = 'string')
    {
        if (!in_array($type, ['string', 'int', 'float'])) {
            throw new \InvalidArgumentException("Invalid type '$type'");
        }

        parent::__construct($name);

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

        $itemString = $this->stripParentheses($value);

        $items = $this->splitValue($itemString);
        $array = $this->toArray($items);

        if ($array === false) {
            throw new AnnotationException("Failed to parse '@{$this->name} {$value}': invalid syntax");
        }

        $annotations[$this->name] = $array;

        return $annotations;
    }

    /**
     * Strip parentheses from value
     *
     * @param string $value
     * @return null|string|string[]
     */
    protected function stripParentheses(string $value)
    {
        return str_starts_with($value, '(')
            ? preg_replace('/^\(((?:"(?:[^"]+|\\\\.)*"|\'(?:[^\']+|\\\\.)*\'|[^\)]+)*)\).*$/', '$1', $value)
            : $value;

    }

    /**
     * Split value into items.
     *
     * @param string $value
     * @return array
     */
    abstract protected function splitValue(string $value): array;

    /**
     * Process matched items.
     *
     * Returns `false` on error.
     *
     * @param array $items
     * @return array|false
     */
    protected function toArray(array $items)
    {
        $result = [];

        foreach ($items as $key => $item) {
            switch ($this->type) {
                case 'string': $regex = '/^\s*(["\']?)(?<value>.*)\1\s*$/'; break;
                case 'int': $regex = '/^\s*(?<value>[\-+]?\d+)\s*$/'; break;
                case 'float': $regex = '/^\s*(?<value>[\-+]?\d+(?:\.\d+)?(?:e\d+)?)\s*$/'; break;
            }

            if (!preg_match($regex, $item, $matches)) {
                return false; // invalid syntax
            }

            $value = $matches['value'];
            settype($value, $this->type);

            if (is_string($key)) {
                $key = trim($key, '\'"');
            }

            $result[$key] = $value;
        }

        return $result;
    }
}
