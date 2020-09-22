<?php

namespace Gifty\Client\Tests\Services;

use Gifty\Client\Exceptions\ApiException;
use Gifty\Client\Resources\GiftCard;
use Gifty\Client\Resources\Transaction;
use Gifty\Client\Services\GiftCardService;
use Gifty\Client\Tests\Common\TestHelper;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Gifty\Client\Services\GiftCardService
 * @covers \Gifty\Client\Services\AbstractService
 * @uses   \Gifty\Client\Services\TransactionService
 * @uses   \Gifty\Client\Resources\AbstractResource
 * @uses   \Gifty\Client\Resources\GiftCard
 */
final class GiftCardServiceTest extends TestCase
{
    use TestHelper;

    public function testGiftCardGet(): void
    {
        // Arrange
        $data = (string)file_get_contents('./tests/Data/GiftCards/Get.json');
        $this->httpClient->mockHandler->append(new Response(200, [], $data));
        $giftCardService = new GiftCardService($this->httpClient);

        // Act
        $giftCardCode = 'code';
        $giftCard = $giftCardService->get($giftCardCode);

        // Assert
        $this->assertInstanceOf(GiftCard::class, $giftCard);
    }

    public function testGiftCardNotFound(): void
    {
        // Arrange
        $data = (string)file_get_contents('./tests/Data/GiftCards/NotFound.json');
        $this->httpClient->mockHandler->append(new Response(404, [], $data));
        $giftCardService = new GiftCardService($this->httpClient);

        // Assert
        $this->expectException(ApiException::class);
        $this->expectExceptionCode(404);

        // Act
        $giftCardCode = 'nonExistingCode';
        $giftCardService->get($giftCardCode);
    }

    public function testIssueGiftCard(): void
    {
        // Arrange
        $transactionsData = (string)file_get_contents('./tests/Data/Transactions/Get.json');
        $this->httpClient->mockHandler->append(new Response(200, [], $transactionsData));
        $giftCardService = new GiftCardService($this->httpClient);

        // Act
        $transaction = $giftCardService->issue(
            'giftCardCode',
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
        $transactionsData = (string)file_get_contents('./tests/Data/Transactions/ValidationError.json');
        $this->httpClient->mockHandler->append(new Response(422, [], $transactionsData));
        $giftCardService = new GiftCardService($this->httpClient);

        // Assert
        $this->expectException(ApiException::class);
        $this->expectExceptionCode(422);
        $this->expectExceptionMessage('The promotional field is required.');

        // Act
        $giftCardService->issue(
            'giftCardCode',
            [
                "amount" => 1250,
                "currency" => "EUR"
            ]
        );
    }

    public function testRedeemGiftCard(): void
    {
        // Arrange
        $transactionsData = (string)file_get_contents('./tests/Data/Transactions/Get.json');
        $this->httpClient->mockHandler->append(new Response(200, [], $transactionsData));
        $giftCardService = new GiftCardService($this->httpClient);

        // Act
        $transaction = $giftCardService->redeem(
            'giftCardCode',
            [
                "amount" => 1250,
                "currency" => "EUR",
                "capture" => false
            ]
        );

        // Assert
        $this->assertInstanceOf(Transaction::class, $transaction);
    }
}
