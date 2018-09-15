<?php

declare(strict_types=1);

namespace Jasny\PhpdocParser\Set;

use Jasny\PhpdocParser\PredefinedSetInterface;
use Jasny\PhpdocParser\Tag\MultiTag;
use Jasny\PhpdocParser\Tag\PhpDocumentor\ExampleTag;
use Jasny\PhpdocParser\Tag\PhpDocumentor\MethodTag;
use Jasny\PhpdocParser\Tag\PhpDocumentor\VarTag;
use Jasny\PhpdocParser\Tag\PhpDocumentor\TypeTag;
use Jasny\PhpdocParser\TagInterface;
use Jasny\PhpdocParser\TagSet;
use Jasny\PhpdocParser\Tag\ModifyTag;
use Jasny\PhpdocParser\Tag\DescriptionTag;
use Jasny\PhpdocParser\Tag\FlagTag;
use Jasny\PhpdocParser\Tag\RegExpTag;
use Jasny\PhpdocParser\Tag\WordTag;

/**
 * PhpDocumentor definitions
 * @static
 */
class PhpDocumentor implements PredefinedSetInterface
{
    /**
     * Disable instantiation.
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * Get the tags
     *
     * @param callable|null $fqsenConvertor  Logic to convert class to FQCN
     * @return TagSet
     */
    public static function tags(?callable $fqsenConvertor = null): TagSet
    {
        return new TagSet([
            new FlagTag('api'),
            new RegExpTag('author', '/^(?:(?<name>(?:[^\<]\S*\s+)*[^\<]\S*)?\s*)?(?:\<(?<email>[^\>]+)\>)?/'),
            new DescriptionTag('copyright'),
            new WordTag('deprecated', true),
            new ExampleTag('example'),
            new FlagTag('ignore'),
            new FlagTag('internal'),
            new WordTag('link'),
            new MultiTag('methods', new MethodTag('method', $fqsenConvertor), 'name'),
            new WordTag('package'),
            new MultiTag('params', new VarTag('param', $fqsenConvertor), 'name'),
            new MultiTag('properties', new VarTag('property', $fqsenConvertor), 'name'),
            new MultiTag(
                'properties',
                new VarTag('property-read', $fqsenConvertor, ['read_only' => true]),
                'name'
            ),
            new MultiTag(
                'properties',
                new VarTag('property-write', $fqsenConvertor, ['write_only' => true]),
                'name'
            ),
            new TypeTag('return', $fqsenConvertor),
            self::fqsen(new WordTag('see'), $fqsenConvertor),
            new WordTag('since'),
            new MultiTag('throws', new TypeTag('throws', $fqsenConvertor)),
            new DescriptionTag('todo'),
            new TypeTag('uses', $fqsenConvertor),
            new TypeTag('used-by', $fqsenConvertor),
            new VarTag('var', $fqsenConvertor)
        ]);
    }

    /**
     * Apply FQCN converter if available
     *
     * @param TagInterface  $tag
     * @param callable|null $fqsenConvertor
     * @return TagInterface
     */
    protected static function fqsen(TagInterface $tag, ?callable $fqsenConvertor): TagInterface
    {
        return isset($fqsenConvertor) ? new ModifyTag($tag, $fqsenConvertor) : $tag;
    }
}
