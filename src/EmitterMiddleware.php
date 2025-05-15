<?php

declare(strict_types=1);

namespace Yiisoft\PsrEmitter;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class EmitterMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly EmitterInterface $emitter = new SapiEmitter(),
    ) {
    }

    /**
     * @throws HeadersHaveBeenSentException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        $this->emitter->emit($response);
        return $response;
    }
}
