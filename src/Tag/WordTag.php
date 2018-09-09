<?php

declare(strict_types=1);

namespace Jasny\PhpdocParser\Tag;

use function Jasny\expect_type;
use function Jasny\str_before;
use function Jasny\str_starts_with;

/**
 * Only use the first word after the tag, ignoring the rest
 */
class WordTag extends AbstractTag
{
    /**
     * Default value if no value is given for tag
     * @var string|bool
     */
    protected $default;

    /**
     * WordTag constructor.
     *
     * @param string      $name
     * @param string|bool $default
     */
    public function __construct(string $name, $default = '')
    {
        parent::__construct($name);

        expect_type($default, ['string', 'bool']);
        $this->default = $default;
    }

    /**
     * Return default if no value is specified
     *
     * @return string|bool
     */
    public function getDefault()
    {
        return $this->default;
    }


    /**
     * Process a notation.
     *
     * @param array  $notations
     * @param string $value
     * @return array
     */
    public function process(array $notations, string $value): array
    {
        if ($value === '') {
            $notations[$this->name] = $this->default;
            return $notations;
        }

        $matches = [];
        $quoted = in_array($value[0], ['"', '\'']) &&
            preg_match('/^(?|"((?:[^"]+|\\\\.)*)"|\'((?:[^\']+|\\\\.)*)\')/', $value, $matches);

        $word = $quoted ? $matches[1] : str_before($value, ' ');
        $notations[$this->name] = $word;

        return $notations;
    }
}
