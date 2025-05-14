<?php

declare(strict_types=1);

namespace Yiisoft\PsrEmitter\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\PsrEmitter\FakeEmitter;

use function PHPUnit\Framework\assertNull;
use function PHPUnit\Framework\assertSame;

final class FakeEmitterTest extends TestCase
{
    public function testBase(): void
    {
        $emitter = new FakeEmitter();

        assertNull($emitter->getLastResponse());

        $response1 = $this->createMock(ResponseInterface::class);
        $emitter->emit($response1);

        assertSame($response1, $emitter->getLastResponse());

        $response2 = $this->createMock(ResponseInterface::class);
        $emitter->emit($response2);

        assertSame($response2, $emitter->getLastResponse());
    }
}
