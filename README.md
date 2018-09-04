Jasny Annotations
===

[![Build Status](https://travis-ci.org/jasny/annotations.svg?branch=master)](https://travis-ci.org/jasny/annotations)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jasny/annotations/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/jasny/annotations/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/jasny/annotations/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/jasny/annotations/?branch=master)
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
use Jasny\Annotations\AnnotationParser;
use Jasny\Annotations\PhpDocumenter;
use Jasny\Annotations\Tag\FlagType;

$doc = (new ReflectionFunction('foo'))->getDocComment();

$customTags = [new FlagTag('important'), new FlagTag('required')];
$tags = PhpDocumenter::tags()->add($customTags);

$parser = new AnnotationParser($tags);
$annotations = $parser->parse($doc);
```
