---
layout: default
title: MapTag
parent: Tags
nav_order: 7
---

MapTag
===

This class should be used for tags with value, that should be represented as associative array.

```php
class Foo
{
    /**
     * @options color=green,type = grass,location = "Earth"
     */
    public $value;
}
```

```php
$doc = (new ReflectionProperty('Foo', 'value'))->getDocComment();
$customTags = [new MapTag('options')];

$notations = getNotations($doc, $customTags);
var_export($notations);
```

Result:

```php
[
    'options' => [
        'color' => 'green',
        'type' => 'grass',
        'location' => 'Earth'
    ]
]
```
