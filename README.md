Jasny Annotations
===

[![Build Status](https://travis-ci.org/jasny/annotations.svg?branch=master)](https://travis-ci.org/jasny/annotations)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jasny/annotations/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/jasny/annotations/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/jasny/annotations/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/jasny/annotations/?branch=master)
[![BCH compliance](https://bettercodehub.com/edge/badge/jasny/annotations?branch=master)](https://bettercodehub.com/)
[![Packagist Stable Version](https://img.shields.io/packagist/v/jasny/annotations.svg)](https://packagist.org/packages/jasny/annotations)
[![Packagist License](https://img.shields.io/packagist/l/jasny/annotations.svg)](https://packagist.org/packages/jasny/annotations)

Parse annotations from PHP DocBlock.

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
