<?php

declare(strict_types=1);

namespace Yiisoft\PsrEmitter\Tests;

use PHPUnit\Framework\TestCase;
use Yiisoft\PsrEmitter\HeadersHaveBeenSentException;
use Yiisoft\PsrEmitter\Tests\Support\InternalMocker\Mock\HeadersSentMock;

use function PHPUnit\Framework\assertSame;

final class HeadersHaveBeenSentExceptionTest extends TestCase
{
    protected function setUp(): void
    {
        HeadersSentMock::$result = true;
        HeadersSentMock::$file = 'test.php';
        HeadersSentMock::$line = 123;
    }

    protected function tearDown(): void
    {
        HeadersSentMock::reset();
    }

    public function testBase(): void
    {
        $exception = new HeadersHaveBeenSentException();

        assertSame('HTTP headers have been sent.', $exception->getName());
        assertSame(
            <<<SOLUTION
            Headers already sent in test.php on line 123.
            Emitter can't send headers once the headers block has already been sent.
            SOLUTION,
            $exception->getSolution(),
        );
    }
}
