<?php

declare(strict_types=1);

namespace Jasny\PhpdocParser;

use function Jasny\expect_type;
use function Jasny\array_without;

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
     * @param iterable|TagInterface[] $tags
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
    public function with(iterable $tags): self
    {
        $tagArray = $tags instanceof \Traversable ? iterator_to_array($tags, false) : $tags;

        return new static(array_merge(array_values($this->tags), $tagArray));
    }

    /**
     * Get set without specified tag(s)
     *
     * @param string ...$keys
     * @return static
     */
    public function without(string ...$keys): self
    {
        return new static(array_without($this->tags, $keys));
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
     * @throws \OutOfBoundsException if tag is unknown.
     */
    public function offsetGet($key): TagInterface
    {
        if (!isset($this->tags[$key])) {
            throw new \OutOfBoundsException("Unknown tag '@{$key}'; Use isset to see if tag is defined.");
        }

        return $this->tags[$key];
    }

    /**
     * Offset to set.
     *
     * @param mixed        $key
     * @param TagInterface $tag  The value to set.
     * @return void
     * @throws \BadMethodCallException
     */
    public function offsetSet($key, $tag): void
    {
        throw new \BadMethodCallException("A tagset is immutable; Use `with()` instead.");
    }

    /**
     * Offset to unset.
     *
     * @param string $key  Tag name
     * @return void
     * @throws \BadMethodCallException
     */
    public function offsetUnset($key): void
    {
        throw new \BadMethodCallException("A tagset is immutable; Use `without()` instead.");
    }
}
