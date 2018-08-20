<?php

declare(strict_types=1);

namespace Jasny\Annotations\Tag;

use Jasny\Annotations\TagInterface;

/**
 * Modify the bahaviour of a tag
 */
class ModifyTag implements TagInterface
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
     */
    public function process(array &$annotations, string $value): void
    {
        $tagAnnotation = [];
        $this->tag->process($tagAnnotations, $value);

        $fn = $this->logic;
        $fn($annotations, $tagAnnotations, $value);
    }
}
