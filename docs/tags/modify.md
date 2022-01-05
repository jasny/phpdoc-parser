---
layout: default
title: ModifyTag
parent: Tags
nav_order: 9
---

ModifyTag
===

This class should be used as a wrapper around other tags classes, to modify obtained notations or to add additional data.

```php
class Foo
{
    /**
     * @todo Make sure method does not return Foo instead of Bar
     * @return Bar
     */
    public function getBar()
    {
        //...
    }
}
```

```php
$postProcess = function(array $notations, array $tagAnnotations, string $value) {
    //Here $tagAnnotations is ['return' => 'Bar'], and $notations
    //contain data of other tags, in this case it is
    //['todo' => 'Make sure method does not return Foo instead of Bar']

    $notations = array_merge($notations, $tagAnnotations);
    $notations['class_exists'] = class_exists($notations['return']); //Add some data

    return $notations;
};

$doc = (new ReflectionClass('Foo'))->getDocComment();
$returnTag = new WordTag('return');
$customTags = [new ModifyTag($returnTag, $postProcess)];

$notations = getNotations($doc, $customTags);
var_export($notations);
```

Result is smth like:

```php
[
    'todo' => 'Make sure method does not return Foo instead of Bar'
    'return' => 'Bar',
    'class_exists' => true
]
```
