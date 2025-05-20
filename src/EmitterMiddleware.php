<?php

declare(strict_types=1);

namespace Yiisoft\PsrEmitter;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * `EmitterMiddleware` is a middleware that sends the response to the client using implementation of `EmitterInterface`.
 */
final class EmitterMiddleware implements MiddlewareInterface
{
    /**
     * @param EmitterInterface $emitter Emitter to send the response.
     */
    public function __construct(
        private readonly EmitterInterface $emitter = new SapiEmitter(),
    ) {
    }

    /**
     * @throws HeadersHaveBeenSentException If headers have already been sent.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        $this->emitter->emit($response);
        return $response;
    }
}
