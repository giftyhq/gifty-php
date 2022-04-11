<?php

namespace Gifty\Client\HttpClient;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

final class HttpResponse implements ResponseInterface
{
    /**
     * @var int
     */
    private int $statusCode;
    /**
     * @var string
     */
    private string $body;
    /**
     * @var string
     */
    private string $protocolVersion;
    /**
     * @var array<array<string>>
     */
    private $headers;

    /**
     * HttpResponse constructor.
     * @param int $statusCode
     * @param string $protocolVersion
     * @param string $body
     * @param array<array<string>> $headers
     */
    public function __construct(
        int $statusCode,
        string $protocolVersion,
        string $body,
        array $headers
    ) {
        $this->statusCode = $statusCode;
        $this->body = $body;
        $this->protocolVersion = $protocolVersion;
        $this->headers = $headers;
    }

    public function getProtocolVersion()
    {
        return $this->protocolVersion;
    }

    public function withProtocolVersion($version)
    {
        return $this;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function hasHeader($name)
    {
        return array_key_exists($name, $this->headers);
    }

    public function getHeader($name)
    {
        if ($this->hasHeader($name) === false) {
            return [];
        }

        return $this->headers[$name];
    }

    public function getHeaderLine($name)
    {
        return implode(',', $this->getHeader($name));
    }

    public function withHeader($name, $value)
    {
        return $this;
    }

    public function withAddedHeader($name, $value)
    {
        return $this;
    }

    public function withoutHeader($name)
    {
        return $this;
    }

    public function getBody()
    {
        return new Stream($this->body);
    }

    public function withBody(StreamInterface $body)
    {
        return $this;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function withStatus($code, $reasonPhrase = '')
    {
        return $this;
    }

    public function getReasonPhrase()
    {
        return '';
    }
}
