<?php

namespace Gifty\Client\HttpClient;

use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;

final class GiftyHttpClient implements GiftyHttpClientInterface
{
    /**
     * @var array<string>
     */
    private array $headers;
    /**
     * @var string
     */
    private string $endpoint;
    /**
     * @var int
     */
    private int $timeout;
    /**
     * @var int
     */
    private int $connectionTimeout;

    public function __construct(string $endpoint, int $timeout = 10, int $connectionTimeout = 2, array $headers = [])
    {
        $this->endpoint = $endpoint;
        $this->timeout = $timeout;
        $this->connectionTimeout = $connectionTimeout;
        $this->headers = $headers;
    }

    /**
     * @inheritDoc
     */
    public static function getClientName(): string
    {
        return 'GiftyHttpClient';
    }

    /**
     * @inheritDoc
     */
    public function setAccessToken(string $token): void
    {
        $this->headers['Authorization'] = 'Bearer ' . $token;
    }

    /**
     * @inheritDoc
     */
    public function request(string $method, string $path, array $options = []): ResponseInterface
    {
        $url = $this->endpoint . $path;
        $this->headers['Content-Type'] = 'application/json';
        $headers = $this->parseHeaders();
        $responseHeaders = [];

        // Apply query parameters
        // @see https://github.com/guzzle/guzzle/blob/82ca75f0b1f130f018febdda29af13086da5dbac/src/Client.php#L420
        if (isset($options['query'])) {
            $value = $options['query'];

            if (is_array($value)) {
                $value = http_build_query($value, '', '&', PHP_QUERY_RFC3986);
            }

            if (!is_string($value)) {
                throw new InvalidArgumentException('query must be a string or array');
            }

            $urlContainsQuery = parse_url($url, PHP_URL_QUERY);

            if ($urlContainsQuery) {
                $url .= '&' . $value;
            } else {
                $url .= '?' . $value;
            }

            unset($options['query']);
        }

        $ch = curl_init();
        curl_setopt_array(
            $ch,
            [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => $this->timeout,
                CURLOPT_CONNECTTIMEOUT => $this->connectionTimeout,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => $method,
                CURLOPT_POSTFIELDS => json_encode($options),
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_HEADERFUNCTION => function ($curl, $header) use (&$responseHeaders) {
                    $len = strlen($header);
                    $header = explode(':', $header, 2);
                    if (count($header) < 2) { // ignore invalid headers
                        return $len;
                    }

                    $responseHeaders[strtolower(trim($header[0]))][] = trim($header[1]);

                    return $len;
                }
            ]
        );
        $curlResponse = curl_exec($ch);

        if (is_bool($curlResponse)) {
            $curlResponse = '';
        }

        $psrResponse = $this->buildPsrResponse($curlResponse, $responseHeaders, $ch);

        curl_close($ch);

        return $psrResponse;
    }

    /**
     * @return array<string>
     */
    private function parseHeaders(): array
    {
        $headers = [];

        foreach ($this->headers as $header => $value) {
            $headers[] = $header . ': ' . $value;
        }

        return $headers;
    }

    /**
     * @param string $response
     * @param array<array<string>> $headers
     * @param \CurlHandle $ch
     * @return ResponseInterface
     */
    private function buildPsrResponse(string $response, array $headers, $ch): ResponseInterface
    {
        return new HttpResponse(
            curl_getinfo($ch, CURLINFO_HTTP_CODE),
            '1.1',
            $response,
            $headers
        );
    }
}
