---
layout: default
title: Tags
nav_order: 2
has_children: true
---


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

* [Summery](summery.md)
* [ArrayTag](array.md)
* [CustomTag](custom.md)
* [DescriptionTag](description.md)
* [ExampleTag](example.md)
* [FlagTag](flag.md)
* [MapTag](map.md)
* [MethodTag](method.md)
* [ModifyTag](modify.md)
* [MultiTag](multi.md)
* [NumberTag](number.md)
* [RegExpTag](regexp.md)
* [VarTag](var.md)
* [WordTag](word.md)

The following function is used in tags documentation, for short reference to parsing:

```php
function getNotations(string $doc, array $tags = []) {
    $tags = PhpDocumentor::tags()->add($tags);

    $parser = new PhpdocParser($tags);
    $notations = $parser->parse($doc);

    return $notations;
}
```

