<?php

declare(strict_types=1);

namespace Jasny\Annotations\Tag;

/**
 * Custom logic for a tag.
 */
class CustomTag extends AbstractTag
{
    /**
     * @var callable
     */
    protected $process;

    /**
     * Class constructor.
     *
     * @param string   $name
     * @param callable $process
     */
    public function __construct(string $name, callable $process)
    {
        parent::__construct($name);

        $this->process = $process;
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
        return call_user_func($this->process, $annotations, $value);
    }
}
