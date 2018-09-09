WordTag
===

Similar to `NumberTag`, takes only the first word from tag's text, ignoring the rest. If a few words are enclosed into quotes, they are considered a single word.

```php
/**
 * @hello "Hello world" is a first thing to say
 */
class Foo
{

}
```

Params that are accepted by `WordTag` constructor:

* name
* default value (used when tag has no text)

```php
$doc = (new ReflectionClass('Foo'))->getDocComment();
$customTags = [new WordTag('hello')];

$notations = getNotations($doc, $customTags);
var_export($notations);
```

Result:

```php
[
    'hello' => 'Hello world'
]
```
