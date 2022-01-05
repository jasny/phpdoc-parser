---
layout: default
title: CustomTag
parent: Tags
nav_order: 3
---

CustomTag
===

`CustomTag` class can be used to create a tag with custom behaviour right during script execution, without the need to predefine a separate tag class.

Having a following class definition:

```php
class Foo
{
    /**
     * @sum 12,3,0,-6
     */
    public $value;
}
```

we define custom parse function, and then obtain notations:

```php
$process = function(array $notations, string $value) {
    $numbers = explode(',', $value);
    $notations[$this->name] = array_sum($numbers);

    return $notations;
};

$doc = (new ReflectionProperty('Foo', 'value'))->getDocComment();
$customTags = [new CustomTag('sum', $process)];

$notations = getNotations($doc, $customTags);
var_export($notations);
```

`$process` function will be applyed to parsing `@sum` tag value. It's also binded to tag instance, so we can use `$this` inside it. The result will be

```php
[
    'sum' => 9
]
```
