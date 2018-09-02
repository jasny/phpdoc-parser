ExampleTag
===

This class is only intended to work with `example` tag. It's already predefined in `PhpDocumentor::tags()`, so we have no need to define it.

```php
/**
 * @example some_dir/some_file.php 12 24  Misterious foo function in action
 */
function foo()
{

}
```

```php
use Jasny\Annotations\{
    AnnotationParser,
    PhpDocumentor,
    DescriptionTag
};

$doc = (new ReflectionFunction('foo'))->getDocComment();
$annotations = getAnnotations($doc);
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
