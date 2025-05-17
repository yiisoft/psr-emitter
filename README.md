<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://yiisoft.github.io/docs/images/yii_logo.svg" height="100px" alt="Yii">
    </a>
    <h1 align="center">Yii PSR Emitter</h1>
    <br>
</p>

[![Latest Stable Version](https://poser.pugx.org/yiisoft/psr-emitter/v)](https://packagist.org/packages/yiisoft/psr-emitter)
[![Total Downloads](https://poser.pugx.org/yiisoft/psr-emitter/downloads)](https://packagist.org/packages/yiisoft/psr-emitter)
[![Build status](https://github.com/yiisoft/psr-emitter/actions/workflows/build.yml/badge.svg?branch=master)](https://github.com/yiisoft/psr-emitter/actions/workflows/build.yml?query=branch%3Amaster)
[![Code Coverage](https://codecov.io/gh/yiisoft/psr-emitter/branch/master/graph/badge.svg)](https://codecov.io/gh/yiisoft/psr-emitter)
[![Mutation testing badge](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fyiisoft%2Fpsr-emitter%2Fmaster)](https://dashboard.stryker-mutator.io/reports/github.com/yiisoft/psr-emitter/master)
[![Static analysis](https://github.com/yiisoft/psr-emitter/actions/workflows/static.yml/badge.svg?branch=master)](https://github.com/yiisoft/psr-emitter/actions/workflows/static.yml?query=branch%3Amaster)
[![type-coverage](https://shepherd.dev/github/yiisoft/psr-emitter/coverage.svg)](https://shepherd.dev/github/yiisoft/psr-emitter)
[![psalm-level](https://shepherd.dev/github/yiisoft/psr-emitter/level.svg)](https://shepherd.dev/github/yiisoft/psr-emitter)

The package provides `EmitterInterface` that responsible for sending PSR-7 HTTP responses and several implementations:

- `SapiEmitter` - sends a response using standard PHP Server API;
- `FakeEmiiter` - a fake emitter that does nothing, except for capturing response (useful for testing purposes).

Additionally, the package provides `EmitterMiddleware` PSR-15 middleware that can be used in an application to send 
a response by any `EmitterInterface` implementation.

## Requirements

- PHP 8.1 or higher.

## Installation

The package could be installed with [Composer](https://getcomposer.org):

```shell
composer require yiisoft/psr-emitter
```

## General usage

Create emitter instance and call `emit()` method with a PSR-7 response:

```php
use Psr\Http\Message\ResponseInterface;
use Yiisoft\PsrEmitter\SapiEmitter;

/** @var Response $response */

$emitter = new SapiEmitter();
$emitter->emit($response);
```

You can customize the buffer size (by default, 8MB) for large response bodies:

```php
use Psr\Http\Message\ResponseInterface;
use Yiisoft\PsrEmitter\SapiEmitter;

/** @var Response $response */

$emitter = new SapiEmitter(4_194_304); // Buffer size is 4MB

// Response content will be sent in chunks of 4MB
$emitter->emit($response);
```

## Documentation

- [Internals](docs/internals.md)

If you need help or have a question, the [Yii Forum](https://forum.yiiframework.com/c/yii-3-0/63) is a good place
for that. You may also check out other [Yii Community Resources](https://www.yiiframework.com/community).

## License

The Yii PSR Emitter is free software. It is released under the terms of the BSD License.
Please see [`LICENSE`](./LICENSE.md) for more information.

Maintained by [Yii Software](https://www.yiiframework.com/).

## Support the project

[![Open Collective](https://img.shields.io/badge/Open%20Collective-sponsor-7eadf1?logo=open%20collective&logoColor=7eadf1&labelColor=555555)](https://opencollective.com/yiisoft)

## Follow updates

[![Official website](https://img.shields.io/badge/Powered_by-Yii_Framework-green.svg?style=flat)](https://www.yiiframework.com/)
[![Twitter](https://img.shields.io/badge/twitter-follow-1DA1F2?logo=twitter&logoColor=1DA1F2&labelColor=555555?style=flat)](https://twitter.com/yiiframework)
[![Telegram](https://img.shields.io/badge/telegram-join-1DA1F2?style=flat&logo=telegram)](https://t.me/yii3en)
[![Facebook](https://img.shields.io/badge/facebook-join-1DA1F2?style=flat&logo=facebook&logoColor=ffffff)](https://www.facebook.com/groups/yiitalk)
[![Slack](https://img.shields.io/badge/slack-join-1DA1F2?style=flat&logo=slack)](https://yiiframework.com/go/slack)
