---
layout: default
title: Home
nav_order: 1
description: "Configurable DocBlock parser from PHP"
permalink: /
---

Configurable DocBlock parser from PHP
{: .fs-6 .fw-300 }

The PHPDoc parser allows you to configure tags including the method how to parse and extract information. This
is inline with phpDocumentor style annotations.

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

