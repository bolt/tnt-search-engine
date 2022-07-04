# TNT Search Engine extension

Author: Ivo Valchev

A search engine for Bolt using TNTSearch

## What does it do?

More intuitive, fuzzy search.

For example, a page has the following title: `Oranges and apples are healty`

By default, the following searches will show up the page as a result:

| Keyword  | Bolt native search  | With this extension |
|---|---|---|
| Oranges  | ✅  | ✅  |
| oranges  | ✅  | ✅  |
| orangse  | ❌  | ✅  |
| oranges and apples | ✅ | ✅ |
| apples and oranges | ❌ | ✅ |
| appels and orangse | ❌ | ✅ |
| healthy apples     | ❌ | ✅ |
| healthy fruit      | ❌ | ✅ |


## Installation

```bash
composer require bolt/tnt-search-engine
```

## Generate the index

For the search to work, TNTSearch requires an index
of all relevant data that will be used for the search.
The extension provides several ways to (re-)generate the index:

### Console command

```
php bin/console tnt-search:generate
```

### Controller-activated

You can (re-)generate the index by making a request to `/bolt/tnt-search/generate`.

## Configuration

You can alter the how the search works in `config/extensions/bolt-tntsearch.yaml`.


## Running PHPStan and Easy Codings Standard

First, make sure dependencies are installed:

```
COMPOSER_MEMORY_LIMIT=-1 composer update
```

And then run ECS:

```
vendor/bin/ecs check src
```
