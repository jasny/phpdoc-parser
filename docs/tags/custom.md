CustomTag
===

`CustomTag` class can be used to create a tag with custom behaviour right during script execution, without the need to predefine a separate tag class.

Having a following class definition:

```php
class Foo
{
    /**
     * @sum 12,3,0,-6
     */
    public $value;
}
```

we define custom parse function and obtain annotations:

```php
$process = function(array $annotations, string $value) {
    $numbers = explode(',', $value);
    $annotations[$this->name] = array_sum($numbers);

    return $annotations;
};

$doc = (new ReflectionProperty('Foo', 'value'))->getDocComment();
$customTags = [new CustomTag('sum', $process)];

$annotations = getAnnotations($doc, $customTags);
var_export($annotations);
```

`$process` function will be applyed to parsing `number` tag value. The result will be

```php
[
    'sum' => 9
]
```
