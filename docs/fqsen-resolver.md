---
layout: default
title: FQSEN Resolver
nav_order: 3
---

FQSEN Resolver
---

FQSEN stands for `Fully Qualified Structural Element Name`. FQSEN convertor is used to expand class name or function name to fully unique name (so with full namespace). For example, `Foo` can be converted to `Zoo\\Foo\\Bar`.

Such convertors are used in this lib. Some tags, that deal with variable types, or classes names, support adding them as a constructor parameter.

For example, `TypeTag`, that can be used for parsing `@return` tag, has the following constructor: `TypeTag($name, $fn = null)`. If provided, convertor expands the type, given as type of returned value in doc-comment. If ommited, the type will stay as it is in doc-comment.

Convertor can be provided in one of two ways:

* `$tags = PhpDocumentor::tags($fn)` - for all the tags, predefined in `PhpDocumentor::tags()`
* `$tags = $tags->add(new TypeTag('footag', $fn))` - for all the tags, that are explicitly added to predefined, it should be passed as a constructor parameter (if it is supported by constructor).

After that create the parser from the tags as `$parser = new PhpdocParser($tags)`.

The resolver function should accept a class name and return an expanded name.

### Example

This example uses [phpDocumentor/TypeResolver](https://github.com/phpDocumentor/TypeResolver).

```php
$reflection = new ReflectionClass('\My\Example\Classy');

$contextFactory = new \phpDocumentor\Reflection\Types\ContextFactory();
$context = $contextFactory->createFromReflector($reflection);

$resolver = new \phpDocumentor\Reflection\FqsenResolver();
$fn = fn(string $class): string => $resolver->resolve($class, $context);

$tags = PhpDocumentor::tags($fn);
$parser = new PhpdocParser($tags);

$doc = $reflection->getDocComment();
$meta = $parser->parse($doc);
```
