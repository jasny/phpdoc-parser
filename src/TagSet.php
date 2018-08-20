<?php

declare(strict_types=1);

namespace Jasny\Annotations;

use function Jasny\expect_type;

/**
 * A set of tags.
 * @immutable
 */
class TagSet implements \IteratorAggregate, \ArrayAccess
{
    /**
     * @var TagInterface[]  Mapped tags
     */
    protected $tags = [];

    /**
     * Claass constructor.
     *
     * @param iterable $tags
     */
    public function __construct(iterable $tags)
    {
        foreach ($tags as $tag) {
            expect_type($tag, TagInterface::class);
            $this->tags[$tag->getName()] = $tag;
        }
    }

    /**
     * Retrieve an external iterator.
     *
     * @retrun \ArrayIterator
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->tags);
    }


    /**
     * Combine sets.
     *
     * @param iterable $tags
     * @return static
     */
    public function add(iterable $tags): self
    {
        if ($tags instanceof \Traversable) {
            $tags = iterator_to_array($tags);
        }

        return new static(array_combine(array_values($this->tags), $tags));
    }

    /**
     * Whether a offset exists.
     *
     * @param string $key  Tag name
     * @return bool
     */
    public function offsetExists($key): bool
    {
        return isset($this->tags[$key]);
    }

    /**
     * Offset to retrieve.
     *
     * @param mixed $key  Tag name
     * @return TagInterface
     * @throws \OutOfRangeException if tag is unknown.
     */
    public function offsetGet($key): TagInterface
    {
        if (!isset($this->tags[$key])) {
            throw new \OutOfRangeException("Unknown tag '@{$key}'; use isset to see if tag is defined.");
        }

        return $this->tags[$key];
    }

    /**
     * Offset to set.
     *
     * @param null         $key
     * @param TagInterface $tag  The value to set.
     * @return void
     * @throws \BadMethodCallException if a key is specified
     */
    public function offsetSet($key, $tag): void
    {
        throw new \BadMethodCallException("A tagset is immutable");
    }

    /**
     * Offset to unset.
     *
     * @param string $key  Tag name
     * @return void
     */
    public function offsetUnset($key): void
    {
        unset($this->tags[$key]);
    }
}
