---
layout: default
title: Summery
parent: Tags
nav_order: 1
---

Summery
===

Used to parse general doc-block description.

It produces data under two keys:

* `summery` - a first line of description
* `description` - whole description (including the first line)

```php
/**
 * This is summery.
 * And this is a second line
 * of multiline description.
 */
function foo()
{

}
```

```php
$doc = (new ReflectionFunction('foo'))->getDocComment();
$customTags = [new Summery()];

$notations = getNotations($doc, $customTags);
var_export($notations);
```

That results in

```php
[
    'summery' => 'This is summery.',
    'description' => "This is summery.\nAnd this is a second line\nof multiline description."
]
```

If desciption contains empty lines, they are not included in parsed result, just skipped.
