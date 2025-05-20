# Internals

## Unit testing

The package is tested with [PHPUnit](https://phpunit.de/). For tests, we mock internals PHP functions with
[xepozz/internal-mocker](https://github.com/xepozz/internal-mocker):

- `headers_sent()`,
- `header()`,
- `header_remove()`,
- `flush()`.

It requires disabling these functions in PHP. You can make it by running a command with the additional flags:  

```shell
php -ddisable_functions=headers_sent,header,header_remove,flush ./vendor/bin/phpunit

# or use composer script
composer test
```

Another way to disable functions is adding them to `php.ini`:

```ini
disable_functions=headers_sent,header,header_remove,flush
```

## Mutation testing

The package tests are checked with [Infection](https://infection.github.io/) mutation framework with
[Infection Static Analysis Plugin](https://github.com/Roave/infection-static-analysis-plugin). To run it:

```shell
./vendor/bin/roave-infection-static-analysis-plugin
```

Running mutation testing requires disabling the same functions as unit testing. Do it in `php.ini`.

## Static analysis

The code is statically analyzed with [Psalm](https://psalm.dev/). To run static analysis:

```shell
./vendor/bin/psalm
```

## Code style

Use [Rector](https://github.com/rectorphp/rector) to make codebase follow some specific rules or
use either newest or any specific version of PHP:

```shell
./vendor/bin/rector
```

## Dependencies

Use [ComposerRequireChecker](https://github.com/maglnet/ComposerRequireChecker) to detect transitive
[Composer](https://getcomposer.org) dependencies:

```shell
./vendor/bin/composer-require-checker
```
