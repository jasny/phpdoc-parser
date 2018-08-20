<?php

declare(strict_types=1);

namespace Jasny\Annotations\Tag;

use Jasny\Annotations\Tag\AbstractTag;

/**
 * Tag is a flag, value is ignored.
 */
class FlagTag extends AbstractTag
{
    /**
     * Process an annotation.
     *
     * @param array $annotations
     * @param string $value
     * @return array
     */
    public function process(array $annotations, string $value): array
    {
        $annotations[$this->name] = true;

        return $annotations;
    }
}
