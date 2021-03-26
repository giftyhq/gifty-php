<?php

namespace Gifty\Client\HttpClient;

use Psr\Http\Message\ResponseInterface;

final class GiftyHttpClient implements GiftyHttpClientInterface
{
    /**
     * @var array<string>
     */
    private $headers;
    /**
     * @var string
     */
    private $endpoint;
    /**
     * @var int
     */
    private $timeout;
    /**
     * @var int
     */
    private $connectionTimeout;

    public function __construct(string $endpoint, int $timeout = 10, int $connectionTimeout = 2, array $headers = [])
    {
        $this->endpoint = $endpoint;
        $this->timeout = $timeout;
        $this->connectionTimeout = $connectionTimeout;
        $this->headers = $headers;
    }

    public static function getClientName(): string
    {
        return 'GiftyHttpClient';
    }

    public function setAccessToken(string $token)
    {
        $this->headers['Authorization'] = 'Bearer ' . $token;
    }

    public function request(string $method, string $path, array $options = []): ResponseInterface
    {
        $url = $this->endpoint . $path;
        $this->headers['Content-Type'] = 'application/json';
        $headers = $this->parseHeaders();
        $responseHeaders = [];

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
    private function buildPsrResponse(
        string $response,
        array $headers,
        $ch
    ): ResponseInterface {
        $response = new HttpResponse(
            curl_getinfo($ch, CURLINFO_HTTP_CODE),
            '1.1',
            $response,
            $headers
        );

        return $response;
    }
}
