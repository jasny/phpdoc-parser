<?php

declare(strict_types=1);

namespace Jasny\Annotations\Tag;

use Jasny\Annotations\AnnotationException;

use function Jasny\expect_type;

/**
 * Only use the first word (that should be a number) after the tag, ignoring the rest
 */
class NumberTag extends AbstractTag
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var int|float
     */
    protected $min;

    /**
     * @var int|float
     */
    protected $max;

    /**
     * NumberTag constructor.
     *
     * @param string    $name
     * @param string    $type  ('int', 'float')
     * @param int|float $min
     * @param int|float $max
     */
    public function __construct(string $name, string $type = 'int', $min = 0, $max = INF)
    {
        if (!in_array($type, ['int', 'integer', 'float'], true)) {
            throw new AnnotationException("NumberTag should be of type int or float, $type given");
        }

        expect_type($min, ['int', 'float']);
        expect_type($max, ['int', 'float']);

        if ($min > $max) {
            throw new AnnotationException("Min value (given $min) should be less than max (given $max)");
        }

        parent::__construct($name);

        $this->type = $type;
        $this->min = $min;
        $this->max = $max;
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
        [$word] = explode(' ', $value, 2);

        if (!is_numeric($word)) {
            throw new AnnotationException("Failed to parse '@{$this->name} $word': not a number");
        }

        if ($word < $this->min) {
            throw new AnnotationException("Parsed value $word should be greater then min value {$this->min}");
        }

        if ($word > $this->max) {
            throw new AnnotationException("Parsed value $word should be less then max value {$this->max}");
        }

        if (in_array($this->type, ['int', 'integer'], true)) {
            $word = (int)$word;
        }

        $annotations[$this->name] = $word + 0;

        return $annotations;
    }
}
