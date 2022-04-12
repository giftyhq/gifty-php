<?php

namespace Gifty\Client\Factories;

use Gifty\Client\HttpClient\GiftyHttpClientInterface;
use Gifty\Client\Services\AbstractService;
use Gifty\Client\Services\GiftCardService;
use Gifty\Client\Services\LocationService;
use Gifty\Client\Services\PackageService;
use Gifty\Client\Services\TransactionService;

final class ServiceFactory
{
    /**
     * @var GiftyHttpClientInterface
     */
    private GiftyHttpClientInterface $httpClient;

    /**
     * @var array<AbstractService>
     */
    private array $services;

    /**
     * @var array<string, class-string<AbstractService>>
     */
    protected static $classMap = [
        'giftCards'    => GiftCardService::class,
        'transactions' => TransactionService::class,
        'locations'    => LocationService::class,
        'packages'     => PackageService::class,
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

    /**
     * @param string $name
     * @return AbstractService|null
     */
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
