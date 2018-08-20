<?php

declare(strict_types=1);

namespace Jasny\Annotations\Tag;

use Jasny\Annotations\TagInterface;

/**
 * Base class for tags
 */
abstract class AbstractTag implements TagInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * Class constructor.
     *
     * @param string $name  Tag name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Get the tag name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
