<?php

declare(strict_types=1);

namespace Jasny\Annotations\Tag;

use Jasny\Annotations\TagInterface;

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
     * Process an annotation.
     *
     * @param array  $annotations
     * @param string $value
     * @return array
     */
    public function process(array $annotations, string $value): array
    {
        $tagAnnotations = $this->tag->process([], $value);
        $annotations = call_user_func($this->logic, $annotations, $tagAnnotations, $value);

        return $annotations;
    }
}
