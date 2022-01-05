---
layout: default
title: Home
nav_order: 1
description: "Configurable DocBlock parser from PHP"
permalink: /
---

Configurable DocBlock parser from PHP
{: .fs-6 .fw-300 }

Annotations aren't implemented in PHP itself which is why this component offers a way to use the PHP doc-blocks as a
place for the well known tag syntax using the `@` char.

The PHPDoc parser allows you to configure tags including the method how to parse and extract information. This
is inline with phpDocumentor style annotations and differs from for instance Doctrine type annotations.

Installation
---

    composer require jasny/phpdoc-parser

Usage
---

```php
/**
 * The description of foo. This function does a lot of thing
 *   which are described here.
 *
 * Some more text here.
 *
 * @important
 * @uses FooReader
 * @internal Why this isn't part of the API.
 *   Multi-line is supported.
 *
 * @param string|callable $first   This is the first param
 * @param int             $second  The second one
 * @return void
 * @throws InvalidArgumentException
 * @throws DoaminException if first argument is not found
 */
function foo($first, int $second)
{
   // ...
}
```

Parse annotations

```php
use Jasny\PHPDocParser\PHPDocParser;
use Jasny\PHPDocParser\Set\PhpDocumentor;
use Jasny\PHPDocParser\Tag\FlagTag;

$doc = (new ReflectionFunction('foo'))->getDocComment();

$customTags = [
    new FlagTag('important')
];
$tags = PhpDocumentor::tags()->with($customTags);

$parser = new PHPDocParser($tags);
$meta = $parser->parse($doc);
```

The result will be the following:

```php
[
    'summery' => "The description of foo",
    'description' => "The description of foo. This function does a lot of thing which are described here.\n\nSome more text.",
    'important' => true,
    'uses' => 'FooReader',
    'internal' => "Why this isn't part of the API. Mutlti-line is supported",
    'params' => [
        'first' => [
            'type' => "string|callable",
            'name' => "first",
            'description' => "This is the first parm"
        ],
        'second' => [
            'type' => "int",
            'name' => "second",
        ]
    ],
    'return' => 'void'
]
```

Tags
---

The following tags are already included in `PhpDocumentor::tags()`:

* `@api`
* `@author`
* `@copyright`
* `@deprecated`
* `@example`
* `@ignore`
* `@internal`
* `@link`
* `@method` (all methods will be grouped in `methods` array)
* `@package`
* `@param` (all params will be grouped in `params` array)
* `@property` (all properties will be grouped in `properties` array)
* `@property-read` (also in `properties` array)
* `@property-write` (also in `properties` array)
* `@return`
* `@see`
* `@since`
* `@throws` (all exceptions will be grouped in `throws` array)
* `@todo`
* `@uses`
* `@used-by`
* `@var`

So if you only need to parse those tags, you can simply do:

```php
//$doc = ...; Get doc-comment string from reflection

$tags = PhpDocumentor::tags();
$parser = new PhpdocParser($tags);
$meta = $parser->parse($doc);
```

Tags classes
---

Here's a list of available tags classes, that should cover most of the use cases:

* [Summery](tags/summery.md)
* [ArrayTag](tags/array.md)
* [CustomTag](tags/custom.md)
* [DescriptionTag](tags/description.md)
* [ExampleTag](tags/example.md)
* [FlagTag](tags/flag.md)
* [MapTag](tags/map.md)
* [MethodTag](tags/method.md)
* [ModifyTag](tags/modify.md)
* [MultiTag](tags/multi.md)
* [NumberTag](tags/number.md)
* [RegExpTag](tags/regexp.md)
* [VarTag](tags/var.md)
* [WordTag](tags/word.md)

The following function is used in tags documentation, for short reference to parsing:

```php
function getNotations(string $doc, array $tags = []) {
    $tags = PhpDocumentor::tags()->add($tags);

    $parser = new PhpdocParser($tags);
    $notations = $parser->parse($doc);

    return $notations;
}
```

FQSEN Resolver
---

FQSEN stands for `Fully Qualified Structural Element Name`. FQSEN convertor is used to expand class name or function name to fully unique name (so with full namespace). For example, `Foo` can be converted to `Zoo\\Foo\\Bar`.

Such convertors are used in this lib. Some tags, that deal with variable types, or classes names, support adding them as a constructor parameter.

For example, `TypeTag`, that can be used for parsing `@return` tag, has the following constructor: `TypeTag($name, $fqsenConvertor = null)`. If provided, convertor expands the type, given as type of returned value in doc-comment. If ommited, the type will stay as it is in doc-comment.

Convertor can be provided in one of two ways:

* `$tags = PhpDocumentor::tags($fn)` - for all the tags, predefined in `PhpDocumentor::tags()`
* `$tags = $tags->add(new TypeTag('footag', $fn))` - for all the tags, that are explicitly added to predefined, it should be passed as a constructor parameter (if it is supported by constructor).

After that create the parser from the tags as `$parser = new PhpdocParser($tags)`.

The resolver function should accept a class name and return an expanded name.

### Example

This example uses [phpDocumentor/TypeResolver](https://github.com/phpDocumentor/TypeResolver).

```php
$reflection = new ReflectionClass('\My\Example\Classy');

$contextFactory = new \phpDocumentor\Reflection\Types\ContextFactory();
$context = $contextFactory->createFromReflector();

$resolver = new \phpDocumentor\Reflection\FqsenResolver();
$fn = fn(string $class): string => $resolver->resolve($class, $context);

$tags = PhpDocumentor::tags($fn);
$parser = new PhpdocParser($tags);

$doc = $reflection->getDocComment();
$meta = $parser->parse($doc);
```
