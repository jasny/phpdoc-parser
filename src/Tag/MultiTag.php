<?php

declare(strict_types=1);

namespace Jasny\PhpdocParser\Tag;

use Jasny\PhpdocParser\TagInterface;
use Jasny\PhpdocParser\PhpdocException;

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
     * @param string       $key    Notation key
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
     * Get the notation key.
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
     * Process an notation.
     *
     * @param array  $notations
     * @param string $value
     * @return array
     * @throws PhpdocException
     */
    public function process(array $notations, string $value): array
    {
        $tagName = $this->tag->getName();

        $tagNotations = $this->tag->process([], $value);

        if (count($tagNotations) !== 1) {
            throw new \LogicException("Unable to parse '@{$tagName}' tag: Multi tags must result in "
                . "exactly one notation per tag.");
        }

        $this->addNotation($notations, $value, reset($tagNotations));

        return $notations;
    }

    /**
     * Add notation.
     *
     * @param array  $notations
     * @param string $value
     * @param mixed  $item
     * @return void
     * @throws PhpdocException
     */
    protected function addNotation(array &$notations, string $value, $item): void
    {
        if (!isset($this->index)) {
            $notations[$this->key][] = $item;
            return;
        }

        $tagName = $this->getName();

        if (!is_array($item) || !isset($item[$this->index])) {
            throw new PhpdocException("Unable to add '@{$tagName} $value' tag: No {$this->index}");
        }

        $index = $item[$this->index];

        if (isset($notations[$this->key][$index])) {
            throw new PhpdocException("Unable to add '@{$tagName} $value' tag: Duplicate {$this->index} "
                . "'$index'");
        }

        $notations[$this->key][$index] = $item;
    }
}
