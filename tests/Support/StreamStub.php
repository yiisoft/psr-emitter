<?php

declare(strict_types=1);

namespace Yiisoft\PsrEmitter\Tests\Support;

use LogicException;
use Psr\Http\Message\StreamInterface;
use Stringable;

use function strlen;

final class StreamStub implements StreamInterface, Stringable
{
    public bool $isGetContentsCalled = false;
    public bool $isReadCalled = false;

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
        return strlen($this->content);
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
        $this->isReadCalled = true;

        return $result;
    }

    public function getContents(): string
    {
        $this->isGetContentsCalled = true;
        return $this->content;
    }

    public function getMetadata(?string $key = null)
    {
        throw new LogicException('Not implemented.');
    }
}
