<?php

namespace Gifty\Client\Services;

use Gifty\Client\Exceptions\ApiException;
use Gifty\Client\HttpClient\GiftyHttpClientInterface;
use Gifty\Client\Resources\Collection;
use Gifty\Client\Resources\GiftCard;
use Gifty\Client\Resources\Transaction;
use Gifty\Client\Services\Operation\All;
use Gifty\Client\Services\Operation\Get;

/**
 * Class TransactionService
 * @package Gifty\Client\Service
 * @method Transaction[]|Collection all()
 * @method Transaction get(string $id)
 */
final class TransactionService extends AbstractService
{
    use All;
    use Get;

    protected const API_PATH = 'transactions';
    protected const API_PATH_PARENT = 'giftcards';

    /**
     * TransactionsService constructor.
     * @param GiftyHttpClientInterface $httpClient
     * @param GiftCard $parentResource
     */
    public function __construct(GiftyHttpClientInterface $httpClient, GiftCard $parentResource)
    {
        parent::__construct($httpClient, $parentResource);
    }

    protected function getResourceClassPath(): string
    {
        return Transaction::class;
    }

    /**
     * @param array<string,string|bool|int> $options
     * @return Transaction
     * @throws ApiException
     */
    public function redeem(array $options = []): Transaction
    {
        $path = $this->buildParentApiPath(['redeem']);
        $response = $this->httpClient->request('POST', $path, $options);
        $resources = $this->parseApiResponse($response);

        return new Transaction($this->httpClient, (array)$resources);
    }

    /**
     * @param array<string,string|bool|int> $options
     * @return Transaction
     * @throws ApiException
     */
    public function issue(array $options = []): Transaction
    {
        $path = $this->buildParentApiPath(['issue']);
        $response = $this->httpClient->request('POST', $path, $options);
        $resources = $this->parseApiResponse($response);

        return new Transaction($this->httpClient, (array)$resources);
    }

    /**
     * @param string $transactionId
     * @param array<string,string|bool|int> $options
     * @return Transaction
     * @throws ApiException
     */
    public function capture(string $transactionId, array $options = []): Transaction
    {
        $path = $this->buildApiPath([$transactionId, 'capture']);
        $response = $this->httpClient->request('POST', $path, $options);
        $resources = $this->parseApiResponse($response);

        return new Transaction($this->httpClient, (array)$resources);
    }

    /**
     * @param string $transactionId
     * @param array<string,string|bool|int> $options
     * @return Transaction
     * @throws ApiException
     */
    public function release(string $transactionId, array $options = []): Transaction
    {
        $path = $this->buildApiPath([$transactionId]);
        $response = $this->httpClient->request('DELETE', $path, $options);
        $resources = $this->parseApiResponse($response);

        return new Transaction($this->httpClient, (array)$resources);
    }
}
