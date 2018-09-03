MethodTag
===

This class should be used for getting value of `method` tag.

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
$customTags = [new MethodTag('getBar')];

$annotations = getAnnotations($doc, $customTags);
```

Result:

```php
[
    'getBar' => [
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

Method's return type and params types can be extended to full namespaced name, so called FQSEN (Fully Qualified Structural Element Name), if fqsen-converter is provided:

```php
$customTags = [new MethodTag('getBar', $fqsenConvertor)];
```

Here `$fqsenConvertor` is a callable, that handles converting class name to unique namespaced name.
