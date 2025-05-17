<?php

declare(strict_types=1);

namespace Yiisoft\PsrEmitter;

use Exception;
use Yiisoft\FriendlyException\FriendlyExceptionInterface;

use function headers_sent;

/**
 * Exception thrown when headers have already been sent.
 */
final class HeadersHaveBeenSentException extends Exception implements FriendlyExceptionInterface
{
    public function getName(): string
    {
        return 'HTTP headers have been sent.';
    }

    public function getSolution(): ?string
    {
        headers_sent($filename, $line);

        return <<<SOLUTION
            Headers already sent in $filename on line $line.
            Emitter can't send headers once the headers block has already been sent.
            SOLUTION;
    }
}
