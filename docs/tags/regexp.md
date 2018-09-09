RegExpTag
===

Used, when extracting needed data from tag's value requires text analysis, and no other additional actions.

As an example we can take `author` tag. It is already included in `PhpDocumentor::tags()`, but we'll imagen that it's not.

```php
/**
 * @author John Doe <doe@example.com>
 */
function foo()
{

}
```

```php
$doc = (new ReflectionFunction('foo'))->getDocComment();
$customTags = [new RegExpTag('author', '/^(?:(?<name>(?:[^\<]\S*\s+)*[^\<]\S*)?\s*)?(?:\<(?<email>[^\>]+)\>)?/')];

$notations = getNotations($doc, $customTags);
var_export($notations);
```

Result:

```php
[
    'author' => [
        'name' => 'John Doe',
        'email' => 'doe@example.com'
    ]
]
```
