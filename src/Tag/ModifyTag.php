<?php

declare(strict_types=1);

namespace Jasny\PhpdocParser\Tag;

use Jasny\PhpdocParser\TagInterface;

/**
 * Modify the behavior of a tag
 */
class ModifyTag implements TagInterface, ProxyTagInterface
{
    /**
     * @var TagInterface
     */
    protected $tag;

    /**
     * @var callable
     */
    protected $logic;


    /**
     * Class constructor.
     *
     * @param TagInterface $tag
     * @param callable     $logic
     */
    public function __construct(TagInterface $tag, callable $logic)
    {
        $this->tag = $tag;
        $this->logic = $logic;
    }

    /**
     * Get the tag name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->tag->getName();
    }

    /**
     * Get the wrapped tag.
     *
     * @return TagInterface
     */
    public function getTag(): TagInterface
    {
        return $this->tag;
    }

    /**
     * Process an notation.
     *
     * @param array  $notations
     * @param string $value
     * @return array
     */
    public function process(array $notations, string $value): array
    {
        $tagNotations = $this->tag->process([], $value);
        $notations = call_user_func($this->logic, $notations, $tagNotations, $value);

        return $notations;
    }
}
