<?php

namespace Gifty\Client\HttpClient;

use Psr\Http\Message\StreamInterface;

final class Stream implements StreamInterface
{
    /**
     * @var string
     */
    private $body;

    /**
     * Stream constructor.
     * @param string $body
     */
    public function __construct(string $body)
    {
        $this->body = $body;
    }

    public function __toString()
    {
        return $this->body;
    }

    public function close()
    {
        return;
    }

    public function detach()
    {
        return null;
    }

    public function getSize()
    {
        return null;
    }

    public function tell()
    {
        return 0;
    }

    public function eof()
    {
        return true;
    }

    public function isSeekable()
    {
        return false;
    }

    public function seek($offset, $whence = SEEK_SET): void
    {
    }

    public function rewind(): void
    {
    }

    public function isWritable()
    {
        return false;
    }

    public function write($string)
    {
        return 0;
    }

    public function isReadable()
    {
        return true;
    }

    public function read($length)
    {
        return '';
    }

    public function getContents()
    {
        return $this->body;
    }

    public function getMetadata($key = null)
    {
        return null;
    }
}
