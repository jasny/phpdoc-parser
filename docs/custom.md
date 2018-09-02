CustomTag
===

`CustomTag` class can be used to create a tag with custom behaviour right during script execution.

Having a following class:

```php
class Foo
{
    /**
     * @sum 12,3,0,-6
     */
    public $value;
}
```

we obtain annotations for `value` property in the following way:

```php
use Jasny\Annotations\{
    AnnotationParser,
    PhpDocumentor,
    CustomTag
};

$process = function(array $annotations, string $value) {
    $numbers = explode(',', $value);
    $annotations[$this->name] = array_sum($numbers);

    return $annotations;
};

$doc = (new ReflectionProperty('Foo', 'value'))->getDocComment();
$customTags = [new CustomTag('sum', $process)];

$annotations = getAnnotations($doc, $customTags);
```

`$process` function will be applyed to parsing `number` tag value, so the result will be

```php
[
    'sum' => 9
]
```
