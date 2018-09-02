ArrayTag
===

This tag should be used for tags with value, that should be represented as a list of some items.

Having a following class:

```php
class Foo
{
    /**
     * @var string
     * @options black,white,green,"and so on"
     */
    public $value;
}
```

we obtain annotations for `value` property in the following way:

```php
use Jasny\Annotations\{
    AnnotationParser,
    PhpDocumentor,
    ArrayTag
};

$doc = (new ReflectionProperty('Foo', 'value'))->getDocComment();
$customTags = [new ArrayTag('options')];

$annotations = getAnnotations($doc, $customTags);
```

and the result will be

```php
[
    'options' => ['black', 'white', 'green', 'and so on']
]
```
