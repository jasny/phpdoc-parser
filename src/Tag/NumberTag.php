<?php

declare(strict_types=1);

namespace Jasny\Annotations\Tag;

use Jasny\Annotations\AnnotationException;

use function jasny\expect_type;

/**
 * Only use the first word after the tag, ignoring the rest
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
     * @param string    $type
     * @param int|float $mim
     * @param int|float $max
     */
    public function __construct(string $name, string $type = 'int', $min = 0, $max = INF)
    {
        expect_type($min, ['int', 'float']);
        expect_type($max, ['int', 'float']);

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

        $annotations[$this->name] = $word + 0;

        return $annotations;
    }
}
