<?php

declare(strict_types=1);

namespace Yiisoft\PsrEmitter\Tests;

use HttpSoft\Message\Response;
use HttpSoft\Message\StreamFactory;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;
use Yiisoft\PsrEmitter\HeadersHaveBeenSentException;
use Yiisoft\PsrEmitter\SapiEmitter;
use Yiisoft\PsrEmitter\Tests\Support\ClosureResponse;
use Yiisoft\PsrEmitter\Tests\Support\InternalMocker\Mock\FlushMock;
use Yiisoft\PsrEmitter\Tests\Support\InternalMocker\Mock\HeaderMock;
use Yiisoft\PsrEmitter\Tests\Support\InternalMocker\Mock\HeaderRemoveMock;
use Yiisoft\PsrEmitter\Tests\Support\InternalMocker\Mock\HeadersSentMock;
use Yiisoft\PsrEmitter\Tests\Support\StreamStub;

use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;

final class SapiEmitterTest extends TestCase
{
    protected function setUp(): void
    {
        HeaderMock::reset();
        HeadersSentMock::reset();
        HeaderRemoveMock::reset();
        FlushMock::reset();
    }

    #[TestWith([2, null])]
    #[TestWith([13, 1])]
    #[TestWith([2, 100])]
    public function testBase(int $expectedFlushCalls, ?int $bufferSize): void
    {
        $content = 'Example body';
        $response = new Response(
            headers: [
                'X-Test' => 1,
                'X-Remove' => ['a', 'b'],
            ],
            body: (new StreamFactory())->createStream($content),
        );
        $emitter = new SapiEmitter($bufferSize);

        $emitter->emit($response);

        assertSame('HTTP/1.1 200 OK', HeaderMock::$status);
        assertSame(
            [
                'X-Test' => ['1'],
                'X-Remove' => ['a', 'b'],
            ],
            HeaderMock::$headers,
        );
        assertSame(1, HeaderRemoveMock::$countWithoutName);
        assertSame(0, HeaderRemoveMock::$countWithName);
        assertSame($expectedFlushCalls, FlushMock::$count);
        $this->expectOutputString($content);
    }

    public function testFullContentEmission(): void
    {
        $content = 'Example body';
        $stream = new StreamStub($content);
        $emitter = new SapiEmitter(12);

        $emitter->emit(
            new Response(body: $stream)
        );

        assertTrue($stream->isGetContentsCalled);
        assertFalse($stream->isReadCalled);
        $this->expectOutputString($content);
    }

    public function testFlushWithoutBody(): void
    {
        $response = new Response(body: new StreamStub());
        $emitter = new SapiEmitter();

        $emitter->emit($response);

        assertSame(1, FlushMock::$count);
    }

    public function testNotReadableStream(): void
    {
        $response = new Response(
            headers: ['X-Test' => 42],
            body: new StreamStub('hello', readable: false),
        );
        $emitter = new SapiEmitter();

        $emitter->emit($response);

        assertSame('HTTP/1.1 200 OK', HeaderMock::$status);
        assertSame(['X-Test' => ['42']], HeaderMock::$headers);
        $this->expectOutputString('');
    }

    public function testNotWriteableStream(): void
    {
        $content = 'Test';
        $response = new Response(
            headers: ['X-Test' => 42],
            body: new StreamStub($content, writable: false),
        );
        $emitter = new SapiEmitter();

        $emitter->emit($response);

        assertSame('HTTP/1.1 200 OK', HeaderMock::$status);
        assertSame(['X-Test' => ['42']], HeaderMock::$headers);
        $this->expectOutputString($content);
    }

    public function testNotSeekableStream(): void
    {
        $content = 'Test';
        $response = new Response(
            body: new StreamStub($content, seekable: false),
        );
        $emitter = new SapiEmitter();

        $emitter->emit($response);

        assertSame('HTTP/1.1 200 OK', HeaderMock::$status);
        $this->expectOutputString($content);
    }

    #[TestWith([2])]
    #[TestWith([10])]
    public function testSeekableStream(int $bufferSize): void
    {
        $content = 'Test';
        $body = new StreamStub($content);
        $body->read(100);
        $response = new Response(body: $body);
        $emitter = new SapiEmitter($bufferSize);

        $emitter->emit($response);

        $this->expectOutputString($content);
    }

    #[TestWith([0])]
    #[TestWith([-1])]
    public function testBufferSizeLessThanOne(int $value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Buffer size must be greater than zero.');
        new SapiEmitter($value);
    }

    public function testHeadersHaveBeenSent(): void
    {
        $response = new Response();
        $emitter = new SapiEmitter();
        HeadersSentMock::$result = true;

        $this->expectException(HeadersHaveBeenSentException::class);
        $emitter->emit($response);
    }

    public function testObLevel(): void
    {
        $expectedLevel = ob_get_level();
        $response = new Response();
        $emitter = new SapiEmitter();

        $emitter->emit($response);

        $actualLevel = ob_get_level();
        assertSame($expectedLevel, $actualLevel);
    }

    #[TestWith(['', 1])]
    #[TestWith(['Example body', 2])]
    public function testExtraObLevel(string $content, int $expectedFlushes): void
    {
        $expectedLevel = ob_get_level();
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('read')->willReturnCallback(static function () use ($content) {
            ob_start();
            ob_start();
            ob_start();
            return $content;
        });
        $stream->method('isReadable')->willReturn(true);
        $stream->method('eof')->willReturnOnConsecutiveCalls(false, true);
        $response = new Response(body: $stream);
        $emitter = new SapiEmitter();

        $emitter->emit($response);

        $actualLevel = ob_get_level();
        assertSame($expectedLevel, $actualLevel);
        assertSame($expectedFlushes, FlushMock::$count);
        $this->expectOutputString($content);
    }

    public function testNotClosedBuffer(): void
    {
        $response1 = new ClosureResponse(static fn() => '1');
        $response2 = new ClosureResponse(
            static function () {
                ob_start();
                return '2';
            }
        );
        $response3 = new ClosureResponse(static fn() => '3');
        $emitter = new SapiEmitter();

        $emitter->emit($response1);
        $emitter->emit($response2);
        $emitter->emit($response3);

        $this->assertSame('123', $this->getActualOutputForAssertion());
    }
}
