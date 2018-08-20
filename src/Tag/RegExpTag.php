<?php

declare(strict_types=1);

namespace Jasny\Annotations\Tag;

use Jasny\Annotations\AnnotationException;
use Jasny\Annotations\Tag\AbstractTag;

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
     * Class constructor.
     * .
     * @param string         $name
     * @param string         $regexp
     * @parma callable|null  $apply   Apply callback on matches
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
     * Process an annotation.
     *
     * @param array  $annotations
     * @param string $value
     * @return array
     */
    public function process(array $annotations, string $value): array
    {
        if (!preg_match($this->regexp, $value, $matches)) {
            throw new AnnotationException("Failed to parse '@{$this->name} $value': invalid syntax");
        }

        $annotations[$this->name] = $matches;

        return $annotations;
    }
}
