<?php

namespace Gifty\Client\Resources;

use Gifty\Client\Exceptions\MissingParameterException;
use Gifty\Client\HttpClient\GiftyHttpClientInterface;
use Gifty\Client\Services\GiftCardTransactionService;

/**
 * Class GiftCard
 * @package Gifty\Client\Resource
 */
final class GiftCard extends AbstractResource
{
    /**
     * @var string
     */
    protected string $apiIdentifierField = 'code';

    /**
     * @var GiftCardTransactionService
     * @deprecated 1.3.0 Retrieving transactions through the GiftCard object is deprecated. Please use
     * $giftyClient->transactions instead.
     * @see TransactionService
     */
    public GiftCardTransactionService $transactions;

    /**
     * GiftCard constructor.
     * @param GiftyHttpClientInterface $httpClient
     * @param array<string|int|bool> $data
     * @throws MissingParameterException
     */
    public function __construct(GiftyHttpClientInterface $httpClient, array $data = [])
    {
        parent::__construct($httpClient, $data);

        $this->container['id'] = $data['id'] ?? null;
        $this->container['code'] = $data['code'];
        $this->container['balance'] = $data['balance'] ?? 0;
        $this->container['currency'] = $data['currency'] ?? null;
        $this->container['promotional'] = $data['promotional'] ?? null;
        $this->container['is_redeemable'] = $data['is_redeemable'] ?? false;
        $this->container['is_issuable'] = $data['is_issuable'] ?? false;
        $this->container['created_at'] = $data['created_at'] ?? null;
        $this->container['transactions'] = $data['transactions'] ?? null;
        $this->transactions = new GiftCardTransactionService($httpClient, $this);
    }

    public static function cleanCode(string $code): string
    {
        $code = str_replace(' ', '', $code);
        return str_replace('-', '', $code);
    }

    public function getId(): ?string
    {
        return $this->container['id'] ? strval($this->container['id']) : null;
    }

    public function getBalance(): int
    {
        return intval($this->container['balance']);
    }

    public function getCurrency(): ?string
    {
        return $this->container['currency'] ? strval($this->container['currency']) : null;
    }

    public function getPromotional(): ?bool
    {
        if ($this->container['promotional'] === null) {
            return null;
        }

        return boolval($this->container['promotional']);
    }

    public function getCreatedAt(): ?string
    {
        return $this->container['created_at'] ? strval($this->container['created_at']) : null;
    }

    public function isRedeemable(): bool
    {
        return $this->container['is_redeemable'] === true;
    }

    public function isIssuable(): bool
    {
        return $this->container['is_issuable'] === true;
    }
}
