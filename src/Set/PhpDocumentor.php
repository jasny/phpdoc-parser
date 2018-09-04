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
            self::fqsen(new WordTag('return'), $fqsenConvertor),
            self::fqsen(new WordTag('see'), $fqsenConvertor),
            new WordTag('since'),
            new MultiTag('throws', self::fqsen(new WordTag('throws'), $fqsenConvertor)),
            new DescriptionTag('todo'),
            self::fqsen(new WordTag('uses'), $fqsenConvertor),
            self::fqsen(new WordTag('used-by'), $fqsenConvertor),
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
