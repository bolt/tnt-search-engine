# Acme ReferenceExtension

Author: YourNameHere

This Bolt extension can be used as a starting point to base your own extensions on.

Installation:

```bash
composer require acmecorp/reference-extension
```


## Running PHPStan and Easy Codings Standard

First, make sure dependencies are installed:

```
COMPOSER_MEMORY_LIMIT=-1 composer update
```

And then run ECS:

```
vendor/bin/ecs check src
```
