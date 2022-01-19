<?php

namespace Gifty\Client\Resources;

use Gifty\Client\Exceptions\MissingParameterException;
use Gifty\Client\HttpClient\GiftyHttpClientInterface;

final class Package extends AbstractResource
{
    /**
     * Package constructor.
     * @param GiftyHttpClientInterface $httpClient
     * @param array<string|int|bool> $data
     * @throws MissingParameterException
     */
    public function __construct(GiftyHttpClientInterface $httpClient, array $data = [])
    {
        parent::__construct($httpClient, $data);

        $this->container['id'] = $data['id'] ?? null;
        $this->container['title'] = $data['title'] ?? null;
        $this->container['description'] = $data['description'] ?? null;
        $this->container['amount'] = $data['amount'] ?? 0;
        $this->container['currency'] = $data['currency'] ?? null;
        $this->container['active'] = $data['active'] ?? false;
    }

    public function getId(): ?string
    {
        return $this->container['id'];
    }

    public function getTitle(): ?string
    {
        return $this->container['title'];
    }

    public function getDescription(): ?string
    {
        return $this->container['description'];
    }

    public function getAmount(): int
    {
        return $this->container['amount'];
    }

    public function getCurrency(): ?string
    {
        return $this->container['currency'];
    }

    public function getActive(): bool
    {
        return $this->container['active'];
    }
}
