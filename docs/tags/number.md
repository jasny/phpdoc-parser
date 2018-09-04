NumberTag
===

This tag takes a first number in tag's value, ignoring anything that follows

```php
class Foo
{
    /**
     * @version 3  App version where property was introduced
     */
    public $amount;
}
```

`NumberTag` constructor takes the following params:

* name
* type (`int` or `float`)
* min value
* max value

```php
$doc = (new ReflectionProperty('Foo', 'amount'))->getDocComment();
$customTags = [new NumberTag('version', 'int', 2, 5)];

$annotations = getAnnotations($doc, $customTags);
var_export($annotations);
```

Result:

```php
[
    'version' => 3
]
```
