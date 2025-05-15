<?php

declare(strict_types=1);

namespace Yiisoft\PsrEmitter;

use Psr\Http\Message\ResponseInterface;

final class FakeEmitter implements EmitterInterface
{
    private ?ResponseInterface $response = null;

    public function emit(ResponseInterface $response): void
    {
        $this->response = $response;
    }

    public function getLastResponse(): ?ResponseInterface
    {
        return $this->response;
    }
}
