<?php

declare(strict_types=1);

namespace Yiisoft\PsrEmitter\Tests\Support;

use LogicException;
use Psr\Http\Message\StreamInterface;
use Stringable;

final class StreamStub implements StreamInterface, Stringable
{
    private bool $isRead = false;

    public function __construct(
        private readonly string $content = '',
        private readonly bool $readable = true,
        private readonly bool $writable = true,
        private readonly bool $seekable = true,
    ) {
    }

    public function __toString(): string
    {
        throw new LogicException('Not implemented.');
        return '';
    }

    public function close(): void
    {
        throw new LogicException('Not implemented.');
    }

    public function detach()
    {
        throw new LogicException('Not implemented.');
    }

    public function getSize(): ?int
    {
        return null;
    }

    public function tell(): int
    {
        throw new LogicException('Not implemented.');
    }

    public function eof(): bool
    {
        return $this->content === '' || $this->isRead;
    }

    public function isSeekable(): bool
    {
        return $this->seekable;
    }

    public function seek(int $offset, int $whence = SEEK_SET): void
    {
        throw new LogicException('Not implemented.');
    }

    public function rewind(): void
    {
        if (!$this->isSeekable()) {
            throw new LogicException('Stream is not seekable.');
        }
        $this->isRead = false;
    }

    public function isWritable(): bool
    {
        return $this->writable;
    }

    public function write(string $string): int
    {
        throw new LogicException('Not implemented.');
    }

    public function isReadable(): bool
    {
        return $this->readable;
    }

    public function read(int $length): string
    {
        $result = $this->isRead ? '' : $this->content;
        $this->isRead = true;
        return $result;
    }

    public function getContents(): string
    {
        throw new LogicException('Not implemented.');
    }

    public function getMetadata(?string $key = null)
    {
        throw new LogicException('Not implemented.');
    }
}
