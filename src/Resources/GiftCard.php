<?php

namespace Gifty\Client\Resources;

use Gifty\Client\Exceptions\MissingParameterException;
use Gifty\Client\HttpClient\GiftyHttpClientInterface;
use Gifty\Client\Services\TransactionService;

/**
 * Class GiftCard
 * @package Gifty\Client\Resource
 */
final class GiftCard extends AbstractResource
{
    /**
     * @var string
     */
    protected $apiIdentifierField = 'code';

    /**
     * @var TransactionService
     */
    public $transactions;

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
        $this->container['balance'] = $data['balance'] ?? null;
        $this->container['currency'] = $data['currency'] ?? null;
        $this->container['promotional'] = $data['promotional'] ?? null;
        $this->container['is_redeemable'] = $data['is_redeemable'] ?? null;
        $this->container['is_issuable'] = $data['is_issuable'] ?? null;
        $this->container['created_at'] = $data['created_at'] ?? null;
        $this->container['transactions'] = $data['transactions'] ?? null;
        $this->transactions = new TransactionService($httpClient, $this);
    }

    public static function cleanCode(string $code): string
    {
        $code = str_replace(' ', '', $code);
        $code = str_replace('-', '', $code);

        return $code;
    }

    public function getId(): ?string
    {
        return $this->container['id'];
    }

    public function getBalance(): ?int
    {
        return $this->container['balance'];
    }

    public function getCurrency(): ?string
    {
        return $this->container['currency'];
    }

    public function getPromotional(): ?bool
    {
        return $this->container['promotional'];
    }

    public function getCreatedAt(): ?string
    {
        return $this->container['created_at'];
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
