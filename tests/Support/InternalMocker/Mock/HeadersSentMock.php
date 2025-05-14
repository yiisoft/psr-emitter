<?php

declare(strict_types=1);

namespace Yiisoft\PsrEmitter\Tests\Support\InternalMocker\Mock;

/**
 * @see headers_sent()
 */
final class HeadersSentMock
{
    public static bool $result = false;
    public static string $file = '';
    public static int $line = 0;

    public static function reset(): void
    {
        self::$result = false;
        self::$file = '';
        self::$line = 0;
    }

    public static function execute(&$file = null, &$line = null): bool
    {
        $file = self::$file;
        $line = self::$line;
        return self::$result;
    }
}
