FlagTag
===

`FlagTag` class is used for tags, that do not have any value. When parsed, that value is set to `true`.

```php
class Foo
{
    /**
     * @required
     */
    public $value;
}
```

```php
$doc = (new ReflectionFunction('foo'))->getDocComment();
$customTags = [new FlagTag('required')];

$notations = getNotations($doc, $customTags);
var_export($notations);
```

That results in

```php
[
    'required' => true
]
```
