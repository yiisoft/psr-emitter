<?php

declare(strict_types=1);

namespace Yiisoft\PsrEmitter;

use Psr\Http\Message\ResponseInterface;

/**
 * `EmitterInterface` is responsible for sending HTTP responses.
 */
interface EmitterInterface
{
    /**
     * Sends the response to the client with headers and body.
     *
     * @param ResponseInterface $response Response object to send.
     *
     * @throws HeadersHaveBeenSentException If headers have already been sent.
     */
    public function emit(ResponseInterface $response): void;
}
