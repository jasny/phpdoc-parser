Jasny Annotations
===

[![Build Status](https://travis-ci.org/jasny/annotations.svg?branch=master)](https://travis-ci.org/jasny/annotations)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jasny/annotations/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/jasny/annotations/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/jasny/annotations/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/jasny/annotations/?branch=master)
[![BCH compliance](https://bettercodehub.com/edge/badge/jasny/annotations?branch=master)](https://bettercodehub.com/)
[![Packagist Stable Version](https://img.shields.io/packagist/v/jasny/annotations.svg)](https://packagist.org/packages/jasny/annotations)
[![Packagist License](https://img.shields.io/packagist/l/jasny/annotations.svg)](https://packagist.org/packages/jasny/annotations)

Configurable annotation parser from PHP.

Annotations aren't implemented in PHP itself which is why this component offers a way to use the PHP doc-blocks as a
place for the well known annotation syntax using the `@` char.

The Jasny Annotation parser allows you to configure tags including the method how to parse and extract information. This
is inline with phpDocumentor style annotations and differs from for instance Doctrine type annotations.

Installation
---

    composer require jasny/annotations

Usage
---

```php
/**
 * The description is ignored. {{@internal As are inline tags.}}
 *
 * @important
 * @uses FooReader
 * @internal Why this isn't part of the API
 *
 * @param string|callable $first   This is the first param
 * @param int             $second  The second one
 * @return void
 */
function foo($first, int $second)
{
   // ...
}
```

Parse annotations

```php
use Jasny\Annotations\{
    AnnotationParser,
    PhpDocumentor,
    Tag\FlagTag
}

$doc = (new ReflectionFunction('foo'))->getDocComment();
$customTags = [
    new FlagTag('important')
];

$annotations = getAnnotations($doc, $customTags);

/**
 * Get annotations from given doc comment for given custom tags
 * @param  string $doc
 * @param  array  $tags
 * @return array
 */
function getAnnotations(string $doc, array $tags = []): array
{
    $tags = PhpDocumentor::tags()->add($tags);

    $parser = new AnnotationParser($tags);
    $annotations = $parser->parse($doc);

    return $annotations;
}
```

The result will be the following:

```php
[
    'important' => true,
    'uses' => 'FooReader',
    'internal' => 'Why this isn\'t part of the API',
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


