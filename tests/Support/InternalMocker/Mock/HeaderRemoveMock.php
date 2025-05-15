<?php

declare(strict_types=1);

namespace Yiisoft\PsrEmitter\Tests\Support\InternalMocker\Mock;

final class HeaderRemoveMock
{
    public static int $countWithName = 0;
    public static int $countWithoutName = 0;

    public static function reset(): void
    {
        self::$countWithName = 0;
        self::$countWithoutName = 0;
    }

    public static function execute(?string $name = null): void
    {
        $name === null
            ? self::$countWithoutName++
            : self::$countWithName++;
    }
}
