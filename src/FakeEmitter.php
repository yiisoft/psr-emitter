<?php

declare(strict_types=1);

namespace Yiisoft\PsrEmitter;

use Psr\Http\Message\ResponseInterface;

/**
 * `FakeEmitter` is a test implementation of `EmitterInterface` that captures the last emitted response.
 */
final class FakeEmitter implements EmitterInterface
{
    private ?ResponseInterface $response = null;

    public function emit(ResponseInterface $response): void
    {
        $this->response = $response;
    }

    /**
     * @return ResponseInterface|null The last emitted response or `null` if no response has been emitted.
     */
    public function getLastResponse(): ?ResponseInterface
    {
        return $this->response;
    }
}
