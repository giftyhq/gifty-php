<?php

namespace Gifty\Client\Tests\Services;

use Gifty\Client\Exceptions\ApiException;
use Gifty\Client\Resources\GiftCard;
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
        $data = (string) file_get_contents('./tests/Data/GiftCards/Get.json');
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
        $data = (string) file_get_contents('./tests/Data/GiftCards/NotFound.json');
        $this->httpClient->mockHandler->append(new Response(404, [], $data));
        $giftCardService = new GiftCardService($this->httpClient);

        // Assert
        $this->expectException(ApiException::class);
        $this->expectExceptionCode(404);

        // Act
        $giftCardCode = 'nonExistingCode';
        $giftCardService->get($giftCardCode);
    }
}
