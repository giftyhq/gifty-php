<?php

namespace Gifty\Client\Tests\Resources;

use Gifty\Client\Exceptions\MissingParameterException;
use Gifty\Client\Resources\GiftCard;
use Gifty\Client\Tests\Common\GiftyMockHttpClient;
use PHPUnit\Framework\TestCase;

/**
 * @covers   \Gifty\Client\Resources\GiftCard
 * @covers   \Gifty\Client\Resources\AbstractResource
 * @uses     \Gifty\Client\Services\GiftCardTransactionService
 * @uses     \Gifty\Client\Services\AbstractService
 */
final class GiftCardTest extends TestCase
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

    /**
     * @dataProvider giftCardData
     * @param array<bool|int|string> $input
     * @param array<bool|int|string> $expectedOutput
     * @throws MissingParameterException
     */
    public function testGiftCardParameters(array $input, array $expectedOutput): void
    {
        // Arrange
        $giftCard = new GiftCard($this->httpClient, $input);

        // Act
        $apiId = $giftCard->getApiIdentifier();
        $id = $giftCard->getId();
        $balance = $giftCard->getBalance();
        $currency = $giftCard->getCurrency();
        $promotional = $giftCard->getPromotional();
        $packageId = $giftCard->getPackageId();
        $created_at = $giftCard->getCreatedAt();
        $expires_at = $giftCard->getExpiresAt();
        $is_redeemable = $giftCard->isRedeemable();
        $is_issuable = $giftCard->isIssuable();
        $is_extendable = $giftCard->isExtendable();

        // Assert
        $this->assertSame($expectedOutput['apiId'], $apiId);
        $this->assertSame($expectedOutput['id'], $id);
        $this->assertSame($expectedOutput['balance'], $balance);
        $this->assertSame($expectedOutput['currency'], $currency);
        $this->assertSame($expectedOutput['promotional'], $promotional);
        $this->assertSame($expectedOutput['package_id'], $packageId);
        $this->assertSame($expectedOutput['created_at'], $created_at);
        $this->assertSame($expectedOutput['expires_at'], $expires_at);
        $this->assertSame($expectedOutput['is_redeemable'], $is_redeemable);
        $this->assertSame($expectedOutput['is_issuable'], $is_issuable);
        $this->assertSame($expectedOutput['is_extendable'], $is_extendable);
    }

    public function testRequireCodeToBeProvided(): void
    {
        // Arrange
        $input = [];

        // Assert
        $this->expectException(MissingParameterException::class);

        // Act
        new GiftCard($this->httpClient, $input);
    }

    /**
     * @return array<int, array<int, array<string, bool|int|string|null>>>
     */
    public function giftCardData(): array
    {
        return [
            [
                [
                    'id' => 'giftCardId',
                    'code' => 'giftcardCode',
                    'balance' => 1250,
                    'currency' => 'EUR',
                    'promotional' => false,
                    'package' => null,
                    'is_redeemable' => true,
                    'is_issuable' => false,
                    'is_extendable' => false,
                    'created_at' => '2018-09-25T11:25:03+00:00',
                    'expires_at' => '2027-09-15T12:42:42+00:00',
                ],
                [
                    'apiId' => 'giftcardCode',
                    'id' => 'giftCardId',
                    'balance' => 1250,
                    'currency' => 'EUR',
                    'promotional' => false,
                    'package_id' => null,
                    'is_redeemable' => true,
                    'is_issuable' => false,
                    'is_extendable' => false,
                    'created_at' => '2018-09-25T11:25:03+00:00',
                    'expires_at' => '2027-09-15T12:42:42+00:00',
                ],
            ],
            [
                [
                    'id' => 'giftCardId',
                    'code' => 'giftcardCode',
                    'balance' => null,
                    'currency' => 'EUR',
                    'promotional' => true,
                    'package' => 'gp_WxBZ9wp4ov6Da1d1rgjal6Dk',
                    'is_redeemable' => false,
                    'is_issuable' => true,
                    'is_extendable' => true,
                    'created_at' => '2018-09-25T11:25:03+00:00',
                    'expires_at' => '2027-09-15T12:42:42+00:00',
                ],
                [
                    'apiId' => 'giftcardCode',
                    'id' => 'giftCardId',
                    'balance' => 0,
                    'currency' => 'EUR',
                    'promotional' => true,
                    'package_id' => 'gp_WxBZ9wp4ov6Da1d1rgjal6Dk',
                    'is_redeemable' => false,
                    'is_issuable' => true,
                    'is_extendable' => true,
                    'created_at' => '2018-09-25T11:25:03+00:00',
                    'expires_at' => '2027-09-15T12:42:42+00:00',
                ],
            ],
            [
                [
                    'code' => 'giftcardCode',
                    'balance' => 0,
                ],
                [
                    'apiId' => 'giftcardCode',
                    'id' => null,
                    'balance' => 0,
                    'currency' => null,
                    'promotional' => null,
                    'package_id' => null,
                    'is_redeemable' => false,
                    'is_issuable' => false,
                    'is_extendable' => false,
                    'created_at' => null,
                    'expires_at' => null,
                ],
            ],
        ];
    }
}
