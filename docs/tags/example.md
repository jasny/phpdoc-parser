ExampleTag
===

This class is only intended to work with `@example` tag. It's instance is already predefined in `PhpDocumentor::tags()`.

```php
/**
 * @example some_dir/some_file.php 12 24  Misterious foo function in action
 */
function foo()
{

}
```

```php
$doc = (new ReflectionFunction('foo'))->getDocComment();

$annotations = getAnnotations($doc);
var_export($annotations);
```

That results in

```php
[
    'example' => [
        'location' => 'some_dir/some_file.php',
        'start_line' => 12,
        'number_of_lines' => 24
    ]
]
```
