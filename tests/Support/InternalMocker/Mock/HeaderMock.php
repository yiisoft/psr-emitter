<?php

declare(strict_types=1);

namespace Yiisoft\PsrEmitter\Tests\Support\InternalMocker\Mock;

use function str_starts_with;

/**
 * @see header()
 */
final class HeaderMock
{
    /**
     * @psalm-var array<string, list<string>>
     */
    public static array $headers = [];
    public static ?string $status = null;

    public static function reset(): void
    {
        self::$headers = [];
        self::$status = null;
    }

    public static function execute(string $header, bool $replace = true, int $responseCode = 0): void
    {
        if (str_starts_with($header, 'HTTP/')) {
            self::$status = $header;
            return;
        }

        [$name, $value] = explode(': ', $header, 2);

        $replace
            ? self::$headers[$name] = [$value]
            : self::$headers[$name][] = $value;
    }
}
