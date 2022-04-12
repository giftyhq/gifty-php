<?php

namespace Gifty\Client\HttpClient;

use Gifty\Client\Exceptions\ApiException;
use Psr\Http\Message\ResponseInterface;

interface GiftyHttpClientInterface
{
    /**
     * GiftyHttpClientInterface constructor.
     * @param string $endpoint
     * @param int $timeout
     * @param int $connectionTimeout
     * @param array<string, string> $headers
     */
    public function __construct(string $endpoint, int $timeout = 10, int $connectionTimeout = 2, array $headers = []);

    /**
     * Returns the name and version of the HTTP client used.
     * This information is passed in the User Agent string.
     * @return string
     */
    public static function getClientName(): string;

    /**
     * @param string $token
     * @return void
     */
    public function setAccessToken(string $token): void;

    /**
     * @param string $method
     * @param string $path
     * @param array<string, array<string, int|bool|string|null>|int|bool|string|null> $options
     * @return ResponseInterface
     * @throws ApiException
     */
    public function request(string $method, string $path, array $options = []): ResponseInterface;
}
