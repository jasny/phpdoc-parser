<?php

declare(strict_types=1);

namespace Jasny\Annotations\Tag;

use Jasny\Annotations\TagInterface;
use Jasny\Annotations\AnnotationException;

/**
 * Tag can exist multiple times
 */
class MultiTag implements TagInterface, ProxyTagInterface
{
    /**
     * @var string
     */
    protected $key;

    /**
     * @var TagInterface
     */
    protected $tag;

    /**
     * @var string|null
     */
    protected $index;


    /**
     * MultiTag constructor.
     *
     * @param string       $key    Annotation key
     * @param TagInterface $tag    Representation of a single tag
     * @param string|null  $index  Item key to index by
     */
    public function __construct(string $key, TagInterface $tag, ?string $index = null)
    {
        $this->key = $key;
        $this->tag = $tag;
        $this->index = $index;
    }


    /**
     * Get the annotation key.
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
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
     * Get the representation of a single tag
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
     * @throws AnnotationException
     */
    public function process(array $annotations, string $value): array
    {
        $tagName = $this->tag->getName();

        $tagAnnotations = $this->tag->process([], $value);

        if (count($tagAnnotations) !== 1) {
            throw new AnnotationException("Unable to parse '@{$tagName} $value' tag: Multi tags must result in "
                . "exactly one annotations per tag.");
        }

        $this->addAnnotation($annotations, $value, reset($tagAnnotations));

        return $annotations;
    }

    /**
     * Add annotation
     *
     * @param array  $annotations
     * @param string $value
     * @param mixed  $item
     * @return void
     * @throws AnnotationException
     */
    protected function addAnnotation(array &$annotations, string $value, array $item): void
    {
        if (!isset($this->index)) {
            $annotations[$this->key][] = $item;
            return;
        }

        $tagName = $this->tag->getName();

        if (!isset($item[$this->index])) {
            throw new AnnotationException("Unable to add '@$tagName $value' tag: No {$this->index}");
        }

        $index = $item[$this->index];

        if (isset($annotations[$this->key][$index])) {
            throw new AnnotationException("Unable to add '@$tagName $value' tag: Duplicate {$this->index} "
                . "'$index'");
        }

        $annotations[$this->key][$index] = $item;
    }
}
