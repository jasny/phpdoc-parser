<?php

declare(strict_types=1);

namespace Jasny\Annotations;

use Jasny\Annotations\TagInterface;
use Jasny\Annotations\TagSet;

/**
 * Class AnnotationParser
 */
class AnnotationParser
{
    /**
     * @var TagSet|TagInterface[]
     */
    protected $tags;

    /**
     * AnnotationParser constructor.
     *
     * @param TagSet|TagInterface[] $tags
     */
    public function __construct(TagSet $tags)
    {
        $this->tags = $tags;
    }

    /**
     * Parse a PHP doc comment and annotations
     *
     * @param string $doc
     * @return array
     */
    public function parse(string $doc): array
    {
        $annotations = [];
        $rawAnnotations = $this->extractAnnotations($doc);

        foreach ($rawAnnotations as $item) {
            if (!isset($this->tags[$item['tag']])) {
                continue;
            }

            $this->tags[$item['tag']]->process($annotations, $item['value'] ?? '');
        }

        return $annotations;
    }

    /**
     * Extract annotations from doc comment
     *
     * @param string $doc
     * @return array
     */
    protected function extractAnnotations(string $doc): array
    {
        $matches = null;
        $regex = '/^\s*(?:\/\*)?\*\s*@(<tag>\S+)(?:\h+(<value>\S.*?)|\h*)(?:\*\*\/)?\r?$/m';

        return preg_match_all($regex, $doc, $matches, PREG_SET_ORDER) ? $matches : [];
    }
}
