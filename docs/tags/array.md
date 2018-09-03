ArrayTag
===

This class should be used for tags with value, that should be represented as a list of items.

Having a following class:

```php
class Foo
{
    /**
     * @options black,white,green,"and so on"
     */
    public $value;
}
```

we obtain annotations for `value` property:

```php
$doc = (new ReflectionProperty('Foo', 'value'))->getDocComment();
$customTags = [new ArrayTag('options')];

$annotations = getAnnotations($doc, $customTags);
```

The result will be

```php
[
    'options' => ['black', 'white', 'green', 'and so on']
]
```
