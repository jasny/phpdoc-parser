TypeTag
===

This tag is used to parse tags that have a syntax `@tag [type] [description]`. Among those are:

* `@return`
* `@throws`
* `@uses`
* `@used-by`

These four tags are already implemented as `TypeTag` in `PhpDocumentor::tags()`. But still let's look at the example:

```php
/**
 * @return Foo  Some obtained Foo object
 */
function foo()
{

}
```

```php
$doc = (new ReflectionClass('Foo'))->getDocComment();
$customTags = [new TypeTag('return', $fqsenConvertor)]; //$fqsenConvertor can be ommited

$notations = getNotations($doc, $customTags);
var_export($notations);
```

Result:

```php
[
    'return' => [
        'type' => 'Bar\\Foo',
        'description' => 'Some obtained Foo object'
    ]
```
