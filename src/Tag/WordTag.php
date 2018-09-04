<?php

declare(strict_types=1);

namespace Jasny\Annotations\Tag;

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
     * Process an annotation.
     *
     * @param array  $annotations
     * @param string $value
     * @return array
     */
    public function process(array $annotations, string $value): array
    {
        if ($value === '') {
            $annotations[$this->name] = $this->default;
            return $annotations;
        }

        $matches = [];
        $quoted = (str_starts_with($value, '"') || str_starts_with($value, '\'')) &&
            preg_match('/^(?|"((?:[^"]+|\\\\.)*)"|\'((?:[^\']+|\\\\.)*)\')/', $value, $matches);

        $word = $quoted ? $matches[1] : str_before($value, ' ');
        $annotations[$this->name] = $word;

        return $annotations;
    }
}
