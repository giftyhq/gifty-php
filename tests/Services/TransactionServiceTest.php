<?php

namespace Gifty\Tests\Services;

use Gifty\Client\Exceptions\ApiException;
use Gifty\Client\Resources\Collection;
use Gifty\Client\Resources\GiftCard;
use Gifty\Client\Resources\Transaction;
use Gifty\Client\Services\GiftCardService;
use Gifty\Tests\Common\TestHelper;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Gifty\Client\Services\TransactionService
 * @covers \Gifty\Client\Services\AbstractService
 * @uses   \Gifty\Client\Services\GiftCardService
 * @uses   \Gifty\Client\Resources\AbstractResource
 * @uses   \Gifty\Client\Resources\Transaction
 * @uses   \Gifty\Client\Resources\GiftCard
 */
final class TransactionServiceTest extends TestCase
{
    use TestHelper;

    public function testTransactionsAreRetrievable(): void
    {
        // Arrange
        $transactionsData = (string) file_get_contents('./tests/Data/Transactions/All.json');
        $response = new Response(200, [], $transactionsData);
        $giftCard = $this->buildGiftCardWithFollowUpResponse($response);

        // Act
        $transactions = $giftCard->transactions->all();

        // Assert
        $this->assertCount(2, $transactions);
        $this->assertInstanceOf(Collection::class, $transactions);
        $this->assertContainsOnlyInstancesOf(Transaction::class, $transactions);
    }

    public function testTransactionGet(): void
    {
        // Arrange
        $transactionsData = (string) file_get_contents('./tests/Data/Transactions/Get.json');
        $response = new Response(200, [], $transactionsData);
        $giftCard = $this->buildGiftCardWithFollowUpResponse($response);

        // Act
        $transaction = $giftCard->transactions->get("transactionId");

        // Assert
        $this->assertInstanceOf(Transaction::class, $transaction);
    }

    public function testTransactionGetNotFound(): void
    {
        // Arrange
        $transactionsData = (string) file_get_contents('./tests/Data/Transactions/NotFound.json');
        $response = new Response(404, [], $transactionsData);
        $giftCard = $this->buildGiftCardWithFollowUpResponse($response);

        // Assert
        $this->expectExceptionCode(404);
        $this->expectException(ApiException::class);

        // Act
        $giftCard->transactions->get("nonExistingTransactionId");
    }

    public function testIssueGiftCard(): void
    {
        // Arrange
        $transactionsData = (string) file_get_contents('./tests/Data/Transactions/Get.json');
        $response = new Response(200, [], $transactionsData);
        $giftCard = $this->buildGiftCardWithFollowUpResponse($response);

        // Act
        $transaction = $giftCard->transactions->issue(
            [
                "amount" => 1250,
                "currency" => "EUR",
                "promotional" => false
            ]
        );

        // Assert
        $this->assertInstanceOf(Transaction::class, $transaction);
    }

    public function testIssueGiftCardWithMissingParameter(): void
    {
        // Arrange
        $transactionsData = (string) file_get_contents('./tests/Data/Transactions/ValidationError.json');
        $response = new Response(200, [], $transactionsData);
        $giftCard = $this->buildGiftCardWithFollowUpResponse($response);

        // Assert
        $this->expectException(ApiException::class);
        $this->expectExceptionCode(422);
        $this->expectDeprecationMessage('The promotional field is required.');

        // Act
        $giftCard->transactions->issue(
            [
                "amount" => 1250,
                "currency" => "EUR"
            ]
        );
    }

    public function testRedeemGiftCard(): void
    {
        // Arrange
        $transactionsData = (string) file_get_contents('./tests/Data/Transactions/Get.json');
        $response = new Response(200, [], $transactionsData);
        $giftCard = $this->buildGiftCardWithFollowUpResponse($response);

        // Act
        $transaction = $giftCard->transactions->redeem(
            [
                "amount" => 1250,
                "currency" => "EUR",
                "capture" => false
            ]
        );

        // Assert
        $this->assertInstanceOf(Transaction::class, $transaction);
    }

    public function testCaptureTransaction(): void
    {
        // Arrange
        $transactionsData = (string) file_get_contents('./tests/Data/Transactions/Get.json');
        $response = new Response(200, [], $transactionsData);
        $giftCard = $this->buildGiftCardWithFollowUpResponse($response);

        // Act
        $transaction = $giftCard->transactions->capture('transactionId');

        // Assert
        $this->assertInstanceOf(Transaction::class, $transaction);
    }

    public function testReleaseTransaction(): void
    {
        // Arrange
        $transactionsData = (string) file_get_contents('./tests/Data/Transactions/Get.json');
        $response = new Response(200, [], $transactionsData);
        $giftCard = $this->buildGiftCardWithFollowUpResponse($response);

        // Act
        $transaction = $giftCard->transactions->release('transactionId');

        // Assert
        $this->assertInstanceOf(Transaction::class, $transaction);
    }


    private function buildGiftCardWithFollowUpResponse(Response $response): GiftCard
    {
        $giftCardData = (string) file_get_contents('./tests/Data/GiftCards/Get.json');
        $this->httpClient->mockHandler->append(new Response(200, [], $giftCardData));
        $this->httpClient->mockHandler->append($response);

        $giftCardService = new GiftCardService($this->httpClient);
        $giftCard = $giftCardService->get('giftCardCode');

        $this->assertInstanceOf(GiftCard::class, $giftCard);

        return $giftCard;
    }
}
