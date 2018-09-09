<?php

declare(strict_types=1);

namespace Jasny\PhpdocParser\Tag;

use Jasny\PhpdocParser\PhpdocException;
use Jasny\PhpdocParser\Tag\AbstractTag;

/**
 * Process using a regular expression
 */
class RegExpTag extends AbstractTag
{
    /**
     * @var string
     */
    protected $regexp;

    /**
     * Class constructor
     *
     * @param string         $name
     * @param string         $regexp
     */
    public function __construct(string $name, string $regexp)
    {
        parent::__construct($name);

        $this->regexp = $regexp;
    }

    /**
     * Get the regular expression
     *
     * @return string
     */
    public function getRegExp(): string
    {
        return $this->regexp;
    }

    /**
     * Process a notation
     *
     * @param array  $notations
     * @param string $value
     * @return array
     */
    public function process(array $notations, string $value): array
    {
        if (!preg_match($this->regexp, $value, $matches)) {
            throw new PhpdocException("Failed to parse '@{$this->name} $value': invalid syntax");
        }

        $notations[$this->name] = $matches;

        return $notations;
    }
}
