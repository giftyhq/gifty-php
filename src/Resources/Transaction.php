<?php

namespace Gifty\Client\Resources;

use Gifty\Client\Exceptions\MissingParameterException;
use Gifty\Client\HttpClient\GiftyHttpClientInterface;

final class Transaction extends AbstractResource
{
    /**
     * Transaction constructor.
     * @param GiftyHttpClientInterface $httpClient
     * @param array<string|int|bool> $data
     * @throws MissingParameterException
     */
    public function __construct(GiftyHttpClientInterface $httpClient, array $data = [])
    {
        parent::__construct($httpClient, $data);

        $this->container['id'] = $data['id'] ?? null;
        $this->container['amount'] = $data['amount'] ?? 0;
        $this->container['currency'] = $data['currency'] ?? null;
        $this->container['status'] = $data['status'] ?? null;
        $this->container['type'] = $data['type'] ?? null;
        $this->container['description'] = $data['description'] ?? null;
        $this->container['is_capturable'] = $data['is_capturable'] ?? false;
        $this->container['captured_at'] = $data['captured_at'] ?? null;
        $this->container['created_at'] = $data['created_at'] ?? null;
    }

    public function getId(): ?string
    {
        return $this->container['id'] ? strval($this->container['id']) : null;
    }

    public function getAmount(): int
    {
        return intval($this->container['amount']);
    }

    public function getCurrency(): ?string
    {
        return $this->container['currency'] ? strval($this->container['currency']) : null;
    }

    public function getStatus(): ?string
    {
        return $this->container['status'] ? strval($this->container['status']) : null;
    }

    public function getType(): ?string
    {
        return $this->container['type'] ? strval($this->container['type']) : null;
    }

    public function getDescription(): ?string
    {
        return $this->container['description'] ? strval($this->container['description']) : null;
    }

    public function getCapturedAt(): ?string
    {
        return $this->container['captured_at'] ? strval($this->container['captured_at']) : null;
    }

    public function getCreatedAt(): ?string
    {
        return $this->container['created_at'] ? strval($this->container['created_at']) : null;
    }

    public function isCapturable(): bool
    {
        return $this->container['is_capturable'] === true;
    }
}
