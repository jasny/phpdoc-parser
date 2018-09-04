MultiTag
===

This tag acts as a wrapper around other tags, grouping them in some way

```php
/**
 * @method string getBar()
 * @method int|Foo getFoo()
 */
class Foo
{

}
```

There's already a `MultiTag` wrapper aroung `@method` tag in `PhpDocumentor::tags()`, but for the sake of example we'll reinit it here, as if it's not there yet.

`MultiTag` constructor accepts the following parameters:

* name
* tag, that will be grouped
* tag field name, which value will be used as a key in result array

```php
$doc = (new ReflectionClass('Foo'))->getDocComment();
$methodTag = new MethodTag('method', $fqsenConverter); //$fqsenConverter can be specified or ommited
$customTags = [new MultiTag('methods', $methodTag, 'name')];

$annotations = getAnnotations($doc, $customTags);
var_export($annotations);
```

Result:

```php
[
    'methods' => [
        'getBar' => [
            'return_type' => 'string',
            'name' => 'getBar'
        ],
        'getFoo' => [
            'return_type' => 'int|Foo',
            'name' => 'getFoo'
        ]
    ]
]
```
