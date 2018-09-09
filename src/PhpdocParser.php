<?php

declare(strict_types=1);

namespace Jasny\PhpdocParser;

use Jasny\PhpdocParser\TagInterface;
use Jasny\PhpdocParser\TagSet;

/**
 * Class PhpdocParser
 */
class PhpdocParser
{
    /**
     * @var TagSet|TagInterface[]
     */
    protected $tags;

    /**
     * PhpdocParser constructor.
     *
     * @param TagSet|TagInterface[] $tags
     */
    public function __construct(TagSet $tags)
    {
        $this->tags = $tags;
    }

    /**
     * Parse a PHP doc comment
     *
     * @param string $doc
     * @return array
     */
    public function parse(string $doc): array
    {
        $notation = [];
        $rawNotations = $this->extractNotations($doc);

        foreach ($rawNotations as $item) {
            if (!isset($this->tags[$item['tag']])) {
                continue;
            }

            $notation = $this->tags[$item['tag']]->process($notation, $item['value'] ?? '');
        }

        return $notation;
    }

    /**
     * Extract notation from doc comment
     *
     * @param string $doc
     * @return array
     */
    protected function extractNotations(string $doc): array
    {
        $matches = null;
        $regex = '/^\s*(?:(?:\/\*)?\*\s*)?@(?<tag>\S+)(?:\h+(?<value>\S.*?)|\h*)(?:\*\*\/)?\r?$/m';

        return preg_match_all($regex, $doc, $matches, PREG_SET_ORDER) ? $matches : [];
    }
}
