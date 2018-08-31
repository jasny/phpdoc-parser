<?php

declare(strict_types=1);

namespace Jasny\Annotations\Set;

use Jasny\Annotations\PredefinedSetInterface;
use Jasny\Annotations\Tag\MultiTag;
use Jasny\Annotations\Tag\PhpDocumentor\ExampleTag;
use Jasny\Annotations\Tag\PhpDocumentor\MethodTag;
use Jasny\Annotations\Tag\PhpDocumentor\VarTag;
use Jasny\Annotations\TagInterface;
use Jasny\Annotations\TagSet;
use Jasny\Annotations\Tag\ModifyTag;
use Jasny\Annotations\Tag\DescriptionTag;
use Jasny\Annotations\Tag\FlagTag;
use Jasny\Annotations\Tag\RegExpTag;
use Jasny\Annotations\Tag\WordTag;

/**
 * PhpDocumenter definitions
 * @static
 */
class PhpDocumenter implements PredefinedSetInterface
{
    /**
     * Disable instantiation.
     */
    private function __construct()
    {
    }

    /**
     * Get the tags
     *
     * @param callable|null $fqsenConverter  Logic to convert class to FQCN
     * @return TagSet
     */
    public static function tags(?callable $fqsenConverter = null): TagSet
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
            new MultiTag('methods', new MethodTag('method', $fqsenConverter), 'name'),
            new WordTag('package'),
            new MultiTag('params', new VarTag('param', $fqsenConverter), 'name'),
            new MultiTag('properties', new VarTag('property', $fqsenConverter), 'name'),
            new MultiTag(
                'properties',
                new VarTag('property-read', $fqsenConverter, ['read_only' => true]),
                'name'
            ),
            new MultiTag(
                'properties',
                new VarTag('property-write', $fqsenConverter, ['write_only' => true]),
                'name'
            ),
            self::fqsen(new WordTag('return'), $fqsenConverter),
            self::fqsen(new WordTag('see'), $fqsenConverter),
            new WordTag('since'),
            new MultiTag('throws', self::fqsen(new WordTag('throws'), $fqsenConverter)),
            new DescriptionTag('todo'),
            self::fqsen(new WordTag('uses'), $fqsenConverter),
            self::fqsen(new WordTag('used-by'), $fqsenConverter),
            new VarTag('var', $fqsenConverter)
        ]);
    }

    /**
     * Apply FQCN converter if available
     *
     * @param TagInterface  $tag
     * @param callable|null $fqsenConverter
     * @return TagInterface
     */
    protected static function fqsen(TagInterface $tag, ?callable $fqsenConverter): TagInterface
    {
        return isset($fqsenConverter) ? new ModifyTag($tag, $fqsenConverter) : $tag;
    }
}
