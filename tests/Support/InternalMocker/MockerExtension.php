<?php

declare(strict_types=1);

namespace Yiisoft\PsrEmitter\Tests\Support\InternalMocker;

use PHPUnit\Event\Test\PreparationStarted;
use PHPUnit\Event\Test\PreparationStartedSubscriber;
use PHPUnit\Event\TestSuite\Started;
use PHPUnit\Event\TestSuite\StartedSubscriber;
use PHPUnit\Runner\Extension\Extension;
use PHPUnit\Runner\Extension\Facade;
use PHPUnit\Runner\Extension\ParameterCollection;
use PHPUnit\TextUI\Configuration\Configuration;
use Xepozz\InternalMocker\Mocker;
use Xepozz\InternalMocker\MockerState;
use Yiisoft\PsrEmitter\Tests\Support\InternalMocker\Mock\FlushMock;
use Yiisoft\PsrEmitter\Tests\Support\InternalMocker\Mock\HeaderMock;
use Yiisoft\PsrEmitter\Tests\Support\InternalMocker\Mock\HeaderRemoveMock;
use Yiisoft\PsrEmitter\Tests\Support\InternalMocker\Mock\HeadersSentMock;

final class MockerExtension implements Extension
{
    public function bootstrap(Configuration $configuration, Facade $facade, ParameterCollection $parameters): void
    {
        $facade->registerSubscribers(
            new class () implements StartedSubscriber {
                public function notify(Started $event): void
                {
                    MockerExtension::load();
                }
            },
            new class () implements PreparationStartedSubscriber {
                public function notify(PreparationStarted $event): void
                {
                    MockerState::resetState();
                }
            },
        );
    }

    public static function load(): void
    {
        $mocks = [
            [
                'namespace' => '',
                'name' => 'headers_sent',
                'function' => fn(
                    string &$file = null,
                    int &$line = null
                ): bool => HeadersSentMock::execute($file, $line),
            ],
            [
                'namespace' => '',
                'name' => 'header',
                'function' => fn(
                    string $header,
                    bool $replace = true,
                    int $responseCode = 0
                ) => HeaderMock::execute($header, $replace, $responseCode),
            ],
            [
                'namespace' => '',
                'name' => 'header_remove',
                'function' => fn(?string $name = null) => HeaderRemoveMock::execute($name),
            ],
            [
                'namespace' => '',
                'name' => 'flush',
                'function' => fn() => FlushMock::execute(),
            ],
        ];

        $mocker = new Mocker();
        $mocker->load($mocks);
        MockerState::saveState();
    }
}
