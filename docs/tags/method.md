MethodTag
===

This class should be used for getting value of `@method` tag.

```php
/**
 * @method string getBar($first, Bar $second, array $third = []) Method to obtain some extra bar
 */
class Foo
{

}
```

```php
$doc = (new ReflectionClass('Foo'))->getDocComment();
$customTags = [new MethodTag('method')];

$notations = getNotations($doc, $customTags);
var_export($notations);
```

Result:

```php
[
    'method' => [
        'return_type' => 'string',
        'name' => 'getBar',
        'params' => [
            'first' => [
                'name' => 'first'
            ],
            'second' => [
                'type' => 'Bar',
                'name' => 'second'
            ],
            'third' => [
                'type' => 'array',
                'name' => 'third',
                'default' => '[]'
            ]
        ]
    ]
]
```

It's better not to use `MethodTag` instance on its own, but instead wrap it in a `MultiTag` instance (see [MultiTag](multi.md)).
