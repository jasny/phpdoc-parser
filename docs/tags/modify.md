ModifyTag
===

This class should be used as a wrapper around other tags classes, to modify obtained annotations or to add additional data.

```php
class Foo
{
    /**
     * @todo Make sure method does not return Foo instead of Bar
     * @return Bar
     */
    public function getBar()
    {
        //...
    }
}
```

```php
$postProcess = function(array $annotations, array $tagAnnotations, string $value) {
    //Here $tagAnnotations is ['return' => 'Bar'], and $annotations
    //contain data of other tags, in this case it is ['todo' => 'Make sure method does not return Foo instead of Bar']

    $annotations = array_merge($annotations, $tagAnnotations);
    $annotations['class_exists'] = class_exists($annotations['return']); //Add some data

    return $annotations;
};

$doc = (new ReflectionClass('Foo'))->getDocComment();
$returnTag = new WordTag('return');
$customTags = [new ModifyTag($returnTag, $postProcess)];

$annotations = getAnnotations($doc, $customTags);
var_export($annotations);
```

Result is smth like:

```php
[
    'todo' => 'Make sure method does not return Foo instead of Bar'
    'return' => 'Bar',
    'class_exists' => true
]
```
