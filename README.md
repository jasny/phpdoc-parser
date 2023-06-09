![jasny-banner](https://user-images.githubusercontent.com/100821/62123924-4c501c80-b2c9-11e9-9677-2ebc21d9b713.png)

Jasny PHPDoc parser
===

[![Build status](https://github.com/jasny/phpdoc-parser/actions/workflows/php.yml/badge.svg)](https://github.com/jasny/phpdoc-parser/actions/workflows/php.yml)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jasny/phpdoc-parser/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/jasny/phpdoc-parser/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/jasny/phpdoc-parser/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/jasny/phpdoc-parser/?branch=master)
[![Packagist Stable Version](https://img.shields.io/packagist/v/jasny/phpdoc-parser.svg)](https://packagist.org/packages/jasny/phpdoc-parser)
[![Packagist License](https://img.shields.io/packagist/l/jasny/phpdoc-parser.svg)](https://packagist.org/packages/jasny/phpdoc-parser)

Configurable DocBlock parser from PHP.

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
use Jasny\PhpdocParser\PhpdocParser;
use Jasny\PhpdocParser\Set\PhpDocumentor;
use Jasny\PhpdocParser\Tag\FlagTag;

$doc = (new ReflectionFunction('foo'))->getDocComment();

$customTags = [
    new FlagTag('important')
];
$tags = PhpDocumentor::tags()->with($customTags);

$parser = new PhpdocParser($tags);
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

* [Summery](https://www.jasny.net/phpdoc-parser/tags/summery.md)
* [ArrayTag](https://www.jasny.net/phpdoc-parser/tags/array.md)
* [CustomTag](https://www.jasny.net/phpdoc-parser/tags/custom.md)
* [DescriptionTag](https://www.jasny.net/phpdoc-parser/tags/description.md)
* [ExampleTag](https://www.jasny.net/phpdoc-parser/tags/example.md)
* [FlagTag](https://www.jasny.net/phpdoc-parser/tags/flag.md)
* [MapTag](https://www.jasny.net/phpdoc-parser/tags/map.md)
* [MethodTag](https://www.jasny.net/phpdoc-parser/tags/method.md)
* [ModifyTag](https://www.jasny.net/phpdoc-parser/tags/modify.md)
* [MultiTag](https://www.jasny.net/phpdoc-parser/tags/multi.md)
* [NumberTag](https://www.jasny.net/phpdoc-parser/tags/number.md)
* [RegExpTag](https://www.jasny.net/phpdoc-parser/tags/regexp.md)
* [VarTag](https://www.jasny.net/phpdoc-parser/tags/var.md)
* [WordTag](https://www.jasny.net/phpdoc-parser/tags/word.md)

The following function is used in tags documentation, for short reference to parsing:

```php
use Jasny\PhpdocParser\PhpdocParser;
use Jasny\PhpdocParser\Set\PhpDocumentor;

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
use Jasny\PhpdocParser\PhpdocParser;
use Jasny\PhpdocParser\Set\PhpDocumentor;
use phpDocumentor\Reflection\Types\ContextFactory;
use phpDocumentor\Reflection\FqsenResolver;

$reflection = new ReflectionClass('\My\Example\Classy');

$contextFactory = new ContextFactory();
$context = $contextFactory->createFromReflector($reflection);

$resolver = new FqsenResolver();
$fn = fn(string $class): string => $resolver->resolve($class, $context);

$tags = PhpDocumentor::tags($fn);
$parser = new PhpdocParser($tags);

$doc = $reflection->getDocComment();
$meta = $parser->parse($doc);
```
