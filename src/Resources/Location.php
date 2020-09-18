<?php

namespace Gifty\Client\Resources;

use Gifty\Client\Exceptions\MissingParameterException;
use Gifty\Client\HttpClient\GiftyHttpClientInterface;

final class Location extends AbstractResource
{

    /**
     * Location constructor.
     * @param GiftyHttpClientInterface $httpClient
     * @param array<string|int|bool> $data
     * @throws MissingParameterException
     */
    public function __construct(GiftyHttpClientInterface $httpClient, array $data = [])
    {
        parent::__construct($httpClient, $data);

        $this->container['id'] = $data['id'] ?? null;
        $this->container['street'] = $data['street'] ?? null;
        $this->container['house_number'] = $data['house_number'] ?? null;
        $this->container['addition'] = $data['addition'] ?? null;
        $this->container['postal_code'] = $data['postal_code'] ?? null;
        $this->container['city'] = $data['city'] ?? null;
    }

    public function getId(): ?string
    {
        return $this->container['id'];
    }

    public function getStreet(): ?string
    {
        return $this->container['street'];
    }

    public function getHouseNumber(): ?string
    {
        return $this->container['house_number'];
    }

    public function getAddition(): ?string
    {
        return $this->container['addition'];
    }

    public function getPostalCode(): ?string
    {
        return $this->container['postal_code'];
    }

    public function getCity(): ?string
    {
        return $this->container['city'];
    }
}
