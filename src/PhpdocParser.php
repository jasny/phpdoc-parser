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
     * @param callable $callback
     * @return array
     */
    public function parse(string $doc, ?callable $callback = null): array
    {
        $notations = [];
        $rawNotations = $this->extractNotations($doc);

        foreach ($rawNotations as $item) {
            if (!isset($this->tags[$item['tag']])) {
                continue;
            }

            $notations = $this->tags[$item['tag']]->process($notations, $item['value'] ?? '');
        }

        if (isset($this->tags['summery'])) {
            $notations = $this->tags['summery']->process($notations, $doc);
        }

        if ($callback) {
            $notations = call_user_func($callback, $notations);
        }

        return $notations;
    }

    /**
     * Extract notations from doc comment
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
