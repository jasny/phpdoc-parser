<?php

declare(strict_types=1);

namespace Jasny\PhpdocParser\Tag;

use Jasny\PhpdocParser\PhpdocException;

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
     * @param string $type   ('string', 'int', 'float')
     */
    public function __construct(string $name, string $type = 'string')
    {
        if (!in_array($type, ['string', 'int', 'float'], true)) {
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
     * Process the notation
     *
     * @param array  $notations
     * @param string $value
     * @return array
     */
    public function process(array $notations, string $value): array
    {
        if ($value === '') {
            $notations[$this->name] = [];

            return $notations;
        }

        $itemString = $this->stripParentheses($value);

        $items = $this->splitValue($itemString);

        try {
            $array = $this->toArray($items);
        } catch (PhpdocException $exception) {
            throw new PhpdocException(
                "Failed to parse '@{$this->name} {$value}': " . $exception->getMessage(),
                0,
                $exception
            );
        }

        $notations[$this->name] = $array;

        return $notations;
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
            ? preg_replace('/^\(((?:"(?:[^"]++|\\\\.)*"|\'(?:[^\']++|\\\\.)*\'|[^\)]++|\))*)\).*$/', '$1', $value)
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
     * Get regular expression to extract the value
     *
     * @return string
     * @throws \UnexpectedValueException
     */
    protected function getExtractValueRegex(): string
    {
        switch ($this->type) {
            case 'string':
                return '/^\s*(["\']?)(?<value>.*)\1\s*$/';
            case 'int':
                return '/^\s*(?<value>[\-+]?\d+)\s*$/';
            case 'float':
                return '/^\s*(?<value>[\-+]?\d+(?:\.\d+)?(?:e\d+)?)\s*$/';
            default:
                throw new \UnexpectedValueException("Unknown type '$this->type'");
        }
    }

    /**
     * Process matched items.
     *
     * @param array $items
     * @return array
     */
    protected function toArray(array $items): array
    {
        $result = [];

        $regex = $this->getExtractValueRegex();

        foreach ($items as $key => $item) {
            if (!preg_match($regex, $item, $matches)) {
                throw new PhpdocException("invalid value '" . addcslashes(trim($item), "'") . "'");
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
