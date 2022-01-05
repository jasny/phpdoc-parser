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
        $rawNotations = $this->joinMultilineNotations($rawNotations);

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
     * Extract notation from doc comment
     *
     * @param string $doc
     * @return array
     */
    protected function extractNotations(string $doc): array
    {
        $matches = null;

        $tag = '\s*@(?<tag>\S+)(?:\h+(?<value>\S.*?)|\h*)';
        $tagContinue = '(?:\040){2}(?<multiline_value>\S.*?)';
        $regex = '/^\s*(?:(?:\/\*)?\*)?(?:' . $tag . '|' . $tagContinue . ')(?:\s*\*\*\/)?\r?$/m';

        return preg_match_all($regex, $doc, $matches, PREG_SET_ORDER) ? $matches : [];
    }

    /**
     * Join multiline notations
     *
     * @param array $rawNotations
     * @return array
     */
    protected function joinMultilineNotations(array $rawNotations): array
    {
        $result = [];
        $tagsNotations = $this->filterTagsNotations($rawNotations);

        foreach ($tagsNotations as $item) {
            if (!empty($item['tag'])) {
                $result[] = $item;
            } else {
                $lastIdx = count($result) - 1;

                if (!isset($result[$lastIdx]['value'])) {
                    $result[$lastIdx]['value'] = '';
                }

                $result[$lastIdx]['value'] = trim($result[$lastIdx]['value'])
                    . ' ' . trim($item['multiline_value']);
            }
        }

        return $result;
    }

    /**
     * Remove everything that goes before tags
     *
     * @param array $rawNotations
     * @return array
     */
    protected function filterTagsNotations(array $rawNotations): array
    {
        for ($i = 0; $i < count($rawNotations); $i++) {
            if (!empty($rawNotations[$i]['tag'])) {
                return array_slice($rawNotations, $i);
            }
        }

        return [];
    }
}
