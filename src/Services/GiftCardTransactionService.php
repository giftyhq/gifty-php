<?php

namespace Gifty\Client\Services;

use Gifty\Client\Exceptions\ApiException;
use Gifty\Client\HttpClient\GiftyHttpClientInterface;
use Gifty\Client\Resources\AbstractResource;
use Gifty\Client\Resources\Collection;
use Gifty\Client\Resources\GiftCard;
use Gifty\Client\Resources\Transaction;

/**
 * Class GiftCardTransactionService
 * @package Gifty\Client\Service
 * @deprecated 1.3.0 Retrieving transactions through the GiftCard object is deprecated.
 */
final class GiftCardTransactionService extends AbstractService
{
    protected const API_PATH = 'transactions';
    protected const API_PATH_PARENT = 'giftcards';

    /**
     * @var GiftCard
     */
    private GiftCard $giftCard;

    /**
     * TransactionsService constructor.
     * @param GiftyHttpClientInterface $httpClient
     * @param GiftCard $parentResource
     */
    public function __construct(GiftyHttpClientInterface $httpClient, GiftCard $parentResource)
    {
        $this->giftCard = $parentResource;

        parent::__construct($httpClient, $parentResource);
    }

    /**
     * @return string
     */
    protected function getResourceClassPath(): string
    {
        return Transaction::class;
    }

    /**
     * @param array<string, string|bool|int|array<string, string|int|null>> $options
     * @return Collection<AbstractResource>
     * @throws ApiException
     */
    public function all(array $options = []): Collection
    {
        $transactionService = new TransactionService($this->httpClient);

        return $transactionService->all(array_merge([
            'giftcard' => $this->giftCard->getId(),
        ], $options));
    }

    /**
     * @param string $id
     * @return AbstractResource
     * @throws ApiException
     */
    public function get(string $id): AbstractResource
    {
        $transactionService = new TransactionService($this->httpClient);

        return $transactionService->get($id);
    }

    /**
     * @param string $transactionId
     * @param array<string,string|bool|int> $options
     * @return Transaction
     * @throws ApiException
     */
    public function capture(string $transactionId, array $options = []): Transaction
    {
        $transactionService = new TransactionService($this->httpClient);

        return $transactionService->capture($transactionId, $options);
    }

    /**
     * @param string $transactionId
     * @param array<string,string|bool|int> $options
     * @return Transaction
     * @throws ApiException
     */
    public function release(string $transactionId, array $options = []): Transaction
    {
        $transactionService = new TransactionService($this->httpClient);

        return $transactionService->release($transactionId, $options);
    }
}
