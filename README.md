Jasny PHPDoc parser
===

[![Build Status](https://travis-ci.org/jasny/annotations.svg?branch=master)](https://travis-ci.org/jasny/annotations)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jasny/annotations/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/jasny/annotations/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/jasny/annotations/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/jasny/annotations/?branch=master)
[![Packagist Stable Version](https://img.shields.io/packagist/v/jasny/annotations.svg)](https://packagist.org/packages/jasny/annotations)
[![Packagist License](https://img.shields.io/packagist/l/jasny/annotations.svg)](https://packagist.org/packages/jasny/annotations)

Configurable DocBlock parser from PHP.

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
 * The description is ignored. {{@internal As are inline tags.}}
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

$tags = PhpDocumentor::tags()->add($tags);

$parser = new AnnotationParser($tags);
$annotations = $parser->parse($doc);
```

The result will be the following:

```php
[
    'important' => true,
    'uses' => 'FooReader',
    'internal' => 'Why this isn\'t part of the API. Mutlti-line is supported',
    'params' => [
        'first' => [
            'type' => 'string|callable',
            'name' => 'first',
        ],
        'second' => [
            'type' => 'int',
            'name' => 'second',
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
* `@throws`
* `@todo`
* `@uses`
* `@used-by`
* `@var`

So if you only need to parse those tags, you can simple do:

```php
//$doc = ...; Get doc-comment string from reflection

$tags = PhpDocumentor::tags();
$parser = new AnnotationParser($tags);
$annotations = $parser->parse($doc);
```

Tags classes
---

Here's a list of available tags classes, that should cover most of the use cases:

* [ArrayTag](docs/tags/array.md)
* [CustomTag](docs/tags/custom.md)
* [DescriptionTag](docs/tags/description.md)
* [ExampleTag](docs/tags/example.md)
* [FlagTag](docs/tags/flag.md)
* [MapTag](docs/tags/map.md)
* [MethodTag](docs/tags/method.md)
* [ModifyTag](docs/tags/modify.md)
* [MultiTag](docs/tags/multi.md)
* [NumberTag](docs/tags/number.md)
* [RegExpTag](docs/tags/regexp.md)
* [VarTag](docs/tags/var.md)
* [WordTag](docs/tags/word.md)
