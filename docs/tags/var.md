VarTag
===

Is used for processing `@var`, `@property` and `@param` tags.

```php
/**
 * @property Bar $value
 */
class Foo
{

}
```

For `@property` and `@param` tags it's better to use a wrapper `MultiTag`, to group them in `properies` and `params` arrays (see [MultiTag](multi.md) for an example).

All these three tags are already included in `PhpDocumentor::tags()`, but still here's a simple example.

```php
$doc = (new ReflectionClass('Foo'))->getDocComment();
$customTags = [new VarTag('property', $fqsenConvertor, ['some-more' => 'data'])];

$annotations = getAnnotations($doc, $customTags);
var_export($annotations);
```

Result:

```php
[
    'property' => [
        'type' => 'Zoo\Baz\Bar',
        'name' => 'value',
        'some-more' => 'data'
    ]
]
```

`$fqsenConvertor` was used to expand property type to namespaced name. Also, as you see, additional custom data can be added to result annotations.
