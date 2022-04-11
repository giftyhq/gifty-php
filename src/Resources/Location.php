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
        return $this->container['id'] ? strval($this->container['id']) : null;
    }

    public function getStreet(): ?string
    {
        return $this->container['street'] ? strval($this->container['street']) : null;
    }

    public function getHouseNumber(): ?string
    {
        return $this->container['house_number'] ? strval($this->container['house_number']) : null;
    }

    public function getAddition(): ?string
    {
        return $this->container['addition'] ? strval($this->container['addition']) : null;
    }

    public function getPostalCode(): ?string
    {
        return $this->container['postal_code'] ? strval($this->container['postal_code']) : null;
    }

    public function getCity(): ?string
    {
        return $this->container['city'] ? strval($this->container['city']) : null;
    }
}
