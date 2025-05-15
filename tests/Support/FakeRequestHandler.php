<?php

declare(strict_types=1);

namespace Yiisoft\PsrEmitter\Tests\Support;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class FakeRequestHandler implements RequestHandlerInterface
{
    private ?ServerRequestInterface $request = null;

    public function __construct(
        private readonly ResponseInterface $response,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->request = $request;
        return $this->response;
    }

    public function getRequest(): ?ServerRequestInterface
    {
        return $this->request;
    }
}
