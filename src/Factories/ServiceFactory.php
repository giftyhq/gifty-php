<?php

namespace Gifty\Client\Factories;

use Gifty\Client\HttpClient\GiftyHttpClientInterface;
use Gifty\Client\Services\AbstractService;
use Gifty\Client\Services\GiftCardService;
use Gifty\Client\Services\LocationService;

final class ServiceFactory
{
    /**
     * @var GiftyHttpClientInterface
     */
    private $httpClient;

    /**
     * @var array<AbstractService>
     */
    private $services;

    /**
     * @var array<string, class-string>
     */
    protected static $classMap = [
        'giftCards' => GiftCardService::class,
        'locations' => LocationService::class,
    ];

    /**
     * AbstractServiceFactory constructor.
     * @param GiftyHttpClientInterface $httpClient
     */
    public function __construct(GiftyHttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
        $this->services = [];
    }

    public function __get(string $name): ?AbstractService
    {
        if (array_key_exists($name, self::$classMap) === false) {
            return null;
        }

        $serviceClass = self::$classMap[$name];

        if (array_key_exists($name, $this->services) === false) {
            $this->services[$name] = new $serviceClass($this->httpClient);
        }

        return $this->services[$name];
    }
}
