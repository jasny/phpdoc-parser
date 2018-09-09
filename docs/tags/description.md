DescriptionTag
===

This one is pretty simple.

```php
/**
 * @todo Add some code to this function
 * @note That code should be good
 */
function foo()
{

}
```

`@todo` tag is predefined in `PhpDocumentor::tags()` as an instance of `DescriptionTag`, so we only need to define `@note` tag instance.

```php
$doc = (new ReflectionFunction('foo'))->getDocComment();
$customTags = [new DescriptionTag('note')];

$notations = getNotations($doc, $customTags);
var_export($notations);
```

That results in

```php
[
    'todo' => 'Add some code to this function',
    'note' => 'That code should be good'
]
```
