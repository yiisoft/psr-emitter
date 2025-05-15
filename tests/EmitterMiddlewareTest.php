<?php

declare(strict_types=1);

namespace Yiisoft\PsrEmitter\Tests;

use HttpSoft\Message\Response;
use HttpSoft\Message\ServerRequest;
use PHPUnit\Framework\TestCase;
use Yiisoft\PsrEmitter\EmitterMiddleware;
use Yiisoft\PsrEmitter\FakeEmitter;
use Yiisoft\PsrEmitter\Tests\Support\FakeRequestHandler;

use function PHPUnit\Framework\assertSame;

final class EmitterMiddlewareTest extends TestCase
{
    public function testBase(): void
    {
        $emitter = new FakeEmitter();
        $middleware = new EmitterMiddleware($emitter);

        $request = new ServerRequest();
        $response = new Response();
        $handler = new FakeRequestHandler($response);

        $result = $middleware->process($request, $handler);

        assertSame($response, $result);
        assertSame($response, $emitter->getLastResponse());
        assertSame($request, $handler->getRequest());
    }
}
