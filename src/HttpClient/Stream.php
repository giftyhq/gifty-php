<?php

namespace Gifty\Client\HttpClient;

use Psr\Http\Message\StreamInterface;

final class Stream implements StreamInterface
{
    /**
     * @var string
     */
    private string $body;

    /**
     * Stream constructor.
     * @param string $body
     */
    public function __construct(string $body)
    {
        $this->body = $body;
    }

    public function __toString(): string
    {
        return $this->body;
    }

    public function close(): void
    {
    }

    public function detach()
    {
        return null;
    }

    public function getSize(): ?int
    {
        return null;
    }

    public function tell(): int
    {
        return 0;
    }

    public function eof(): bool
    {
        return true;
    }

    public function isSeekable(): bool
    {
        return false;
    }

    public function seek($offset, $whence = SEEK_SET): void
    {
    }

    public function rewind(): void
    {
    }

    public function isWritable(): bool
    {
        return false;
    }

    public function write($string): int
    {
        return 0;
    }

    public function isReadable(): bool
    {
        return true;
    }

    public function read($length): string
    {
        return '';
    }

    public function getContents(): string
    {
        return $this->body;
    }

    public function getMetadata($key = null)
    {
        return null;
    }
}
