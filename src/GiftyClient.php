<?php

namespace Gifty\Client;

use Gifty\Client\Exceptions\ApiException;
use Gifty\Client\Factories\ServiceFactory;
use Gifty\Client\HttpClient\GiftyHttpClient;
use Gifty\Client\HttpClient\GiftyHttpClientInterface;
use Gifty\Client\Services\GiftCardService;
use Gifty\Client\Services\LocationService;
use Gifty\Client\Services\PackageService;
use Gifty\Client\Services\TransactionService;

/**
 * Class GiftyClient
 * @package Gifty\Client
 * @property GiftCardService $giftCards
 * @property TransactionService $transactions
 * @property LocationService $locations
 * @property PackageService $packages
 */
final class GiftyClient
{
    public const VERSION = '1.3.0';
    private const USER_AGENT_FORMAT = 'Gifty/Gifty-PHP/%s/PHP/%s/%s';

    /**
     * @var string
     */
    private string $apiEndpoint = 'https://api.gifty.nl/v1/';

    /**
     * @var array<string, string>
     */
    private array $apiHeaders = [];

    /**
     * @var GiftyHttpClientInterface
     */
    private GiftyHttpClientInterface $httpClient;

    /**
     * @var ServiceFactory
     */
    private ServiceFactory $serviceFactory;

    /**
     * GiftyClient constructor.
     * @param string $apiKey
     * @param array<string, string|array<string, string>> $options
     * @param GiftyHttpClientInterface|null $httpClient
     */
    public function __construct(string $apiKey, array $options = [], ?GiftyHttpClientInterface $httpClient = null)
    {
        if (isset($options['api_endpoint']) && is_string($options['api_endpoint'])) {
            $this->setApiEndpoint($options['api_endpoint']);
        }

        if (isset($options['api_headers']) && is_array($options['api_headers'])) {
            $this->setApiHeaders($options['api_headers']);
        }

        $this->setHttpClient($httpClient);
        $this->httpClient->setAccessToken($apiKey);
    }

    /**
     * @param string $name
     * @return Services\AbstractService|null
     */
    public function __get(string $name)
    {
        if (false === isset($this->serviceFactory)) {
            $this->serviceFactory = new ServiceFactory($this->httpClient);
        }

        return $this->serviceFactory->__get($name);
    }

    public function validateApiKey(): bool
    {
        try {
            $this->locations->all();
        } catch (ApiException $e) {
            return false;
        }

        return true;
    }

    /**
     * Set the HTTP client to our default (Curl) or use the user
     * specified client. This is useful for testing, so we can use
     * the Guzzle Mock client.
     * @param GiftyHttpClientInterface|null $httpClient
     * @return GiftyClient
     */
    private function setHttpClient(?GiftyHttpClientInterface $httpClient = null): self
    {
        if ($httpClient !== null) {
            $this->httpClient = $httpClient;

            return $this;
        }

        $this->httpClient = new GiftyHttpClient(
            $this->apiEndpoint,
            10,
            2,
            array_merge(
                [
                'User-Agent' => $this->getUserAgent(GiftyHttpClient::getClientName()),
                'Accept' => 'application/json',
                ],
                $this->apiHeaders,
            )
        );

        return $this;
    }

    private function setApiEndpoint(string $endpoint): self
    {
        $this->apiEndpoint = $endpoint;

        return $this;
    }

    /**
     * @param array<string, string> $headers
     */
    private function setApiHeaders(array $headers): self
    {
        $this->apiHeaders = $headers;

        return $this;
    }

    private function getUserAgent(string $httpClientName): string
    {
        return sprintf(
            self::USER_AGENT_FORMAT,
            self::VERSION,
            phpversion(),
            $httpClientName
        );
    }
}
