<?php

namespace Gifty\Client\Tests\Common;

use Gifty\Client\Exceptions\ApiException;
use Gifty\Client\HttpClient\GiftyHttpClientInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;

final class GiftyMockHttpClient implements GiftyHttpClientInterface
{

    /**
     * @var Client
     */
    private $guzzleClient;

    /**
     * @var MockHandler
     */
    public $mockHandler;

    /**
     * @inheritDoc
     */
    public function __construct(
        string $endpoint = 'https://api.gifty.nl/v1',
        int $timeout = 10,
        int $connectionTimeout = 2,
        array $headers = []
    ) {
        $this->mockHandler = new MockHandler();
        $this->guzzleClient = new Client(
            [
                'base_uri' => $endpoint,
                'handler' => HandlerStack::create($this->mockHandler),
                RequestOptions::HTTP_ERRORS => false,
                RequestOptions::TIMEOUT => $timeout,
                RequestOptions::CONNECT_TIMEOUT => $connectionTimeout,
                RequestOptions::HEADERS => $headers,
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public static function getClientName(): string
    {
        $version = 'undefined';

        if (defined('\GuzzleHttp\ClientInterface::MAJOR_VERSION')) {
            $version = constant('\GuzzleHttp\ClientInterface::MAJOR_VERSION');
        } elseif (defined('\GuzzleHttp\ClientInterface::VERSION')) {
            $version = constant('\GuzzleHttp\ClientInterface::VERSION');
        }

        return sprintf('GuzzleHttp/%d', $version);
    }

    /**
     * @inheritDoc
     */
    public function setAccessToken(string $token): void
    {
        $configuration = $this->guzzleClient->getConfig();
        $configuration[RequestOptions::HEADERS]['Authorization'] = 'Bearer ' . $token;

        $this->guzzleClient = new Client($configuration);
    }

    /**
     * @param string $method
     * @param string $path
     * @param array<string, bool|int|string> $options
     * @return ResponseInterface
     * @throws ApiException
     */
    public function request(string $method, string $path, array $options = []): ResponseInterface
    {
        try {
            return $this->guzzleClient->request(
                $method,
                $path,
                [
                    RequestOptions::FORM_PARAMS => $options
                ]
            );
        } catch (GuzzleException $e) {
            throw new ApiException($e->getMessage(), $e->getCode(), $e->getPrevious());
        }
    }
}
