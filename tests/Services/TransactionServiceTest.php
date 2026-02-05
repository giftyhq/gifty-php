<?php

namespace Gifty\Client\Tests\Services;

use Gifty\Client\Exceptions\ApiException;
use Gifty\Client\Resources\Collection;
use Gifty\Client\Resources\Transaction;
use Gifty\Client\Services\TransactionService;
use Gifty\Client\Tests\Common\GiftyMockHttpClient;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Gifty\Client\Services\GiftCardTransactionService
 * @covers \Gifty\Client\Services\AbstractService
 * @uses   \Gifty\Client\Services\GiftCardService
 * @uses   \Gifty\Client\Resources\AbstractResource
 * @uses   \Gifty\Client\Resources\Transaction
 * @uses   \Gifty\Client\Resources\GiftCard
 */
final class TransactionServiceTest extends TestCase
{
    /**
     * @var GiftyMockHttpClient
     */
    protected $httpClient;

    protected function setUp(): void
    {
        $this->httpClient = new GiftyMockHttpClient();

        parent::setUp();
    }

    public function testTransactionsAreRetrievable(): void
    {
        // Arrange
        $transactionsData = (string) file_get_contents('./tests/Data/Transactions/All.json');
        $response = new Response(200, [], $transactionsData);
        $this->httpClient->mockHandler->append($response);

        // Act
        $transactionService = new TransactionService($this->httpClient);
        $transactions = $transactionService->all();

        // Assert
        $this->assertCount(2, $transactions);
        $this->assertInstanceOf(Collection::class, $transactions);
        $this->assertContainsOnlyInstancesOf(Transaction::class, $transactions);
    }

    public function testTransactionsAreFilterable(): void
    {
        // Arrange
        $transactionsData = (string) file_get_contents('./tests/Data/Transactions/AllFiltered.json');
        $response = new Response(200, [], $transactionsData);
        $this->httpClient->mockHandler->append($response);

        // Act
        $transactionService = new TransactionService($this->httpClient);
        $transactions = $transactionService->all([
            'giftcard' => 'gc_abc'
        ]);

        // Assert
        $this->assertCount(1, $transactions);
        $this->assertInstanceOf(Collection::class, $transactions);
        $this->assertContainsOnlyInstancesOf(Transaction::class, $transactions);
    }

    public function testTransactionGet(): void
    {
        // Arrange
        $transactionsData = (string) file_get_contents('./tests/Data/Transactions/Get.json');
        $response = new Response(200, [], $transactionsData);
        $this->httpClient->mockHandler->append($response);

        // Act
        $transactionService = new TransactionService($this->httpClient);
        $transaction = $transactionService->get("transactionId");

        // Assert
        $this->assertInstanceOf(Transaction::class, $transaction);
    }

    public function testTransactionGetNotFound(): void
    {
        // Arrange
        $transactionsData = (string) file_get_contents('./tests/Data/Transactions/NotFound.json');
        $response = new Response(404, [], $transactionsData);
        $this->httpClient->mockHandler->append($response);

        // Assert
        $this->expectExceptionCode(404);
        $this->expectException(ApiException::class);

        // Act
        $transactionService = new TransactionService($this->httpClient);
        $transactionService->get("nonExistingTransactionId");
    }

    public function testCaptureTransaction(): void
    {
        // Arrange
        $transactionsData = (string) file_get_contents('./tests/Data/Transactions/Get.json');
        $response = new Response(200, [], $transactionsData);
        $this->httpClient->mockHandler->append($response);

        // Act
        $transactionService = new TransactionService($this->httpClient);
        $transaction = $transactionService->capture('transactionId');

        // Assert
        $this->assertInstanceOf(Transaction::class, $transaction);
    }

    public function testReleaseTransaction(): void
    {
        // Arrange
        $transactionsData = (string) file_get_contents('./tests/Data/Transactions/Get.json');
        $response = new Response(200, [], $transactionsData);
        $this->httpClient->mockHandler->append($response);

        // Act
        $transactionService = new TransactionService($this->httpClient);
        $transaction = $transactionService->release('transactionId');

        // Assert
        $this->assertInstanceOf(Transaction::class, $transaction);
    }

    public function testRefundTransaction(): void
    {
        // Arrange
        $transactionsData = (string) file_get_contents('./tests/Data/Transactions/Get.json');
        $response = new Response(200, [], $transactionsData);
        $this->httpClient->mockHandler->append($response);

        // Act
        $transactionService = new TransactionService($this->httpClient);
        $transaction = $transactionService->refund('transactionId', [
            'amount' => 1250,
            'currency' => 'EUR',
            'reason' => 'CUSTOMER_REQUEST',
            'reason_description' => 'Customer returned one item from the order'
        ]);

        // Assert
        $this->assertInstanceOf(Transaction::class, $transaction);
    }
}
