<?php

declare(strict_types=1);

namespace Yiisoft\PsrEmitter\Tests\Support\InternalMocker\Mock;

final class FlushMock
{
    public static int $count = 0;

    public static function reset(): void
    {
        self::$count = 0;
    }

    public static function execute(): void
    {
        self::$count++;
    }
}
