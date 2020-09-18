<?php

namespace Gifty\Tests\Resources;

use Gifty\Client\Exceptions\MissingParameterException;
use Gifty\Client\Resources\GiftCard;
use Gifty\Tests\Common\TestHelper;
use PHPUnit\Framework\TestCase;

/**
 * @covers   \Gifty\Client\Resources\GiftCard
 * @covers   \Gifty\Client\Resources\AbstractResource
 * @uses     \Gifty\Client\Services\TransactionService
 * @uses     \Gifty\Client\Services\AbstractService
 */
final class GiftCardTest extends TestCase
{
    use TestHelper;

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
        $created_at = $giftCard->getCreatedAt();
        $is_redeemable = $giftCard->isRedeemable();
        $is_issuable = $giftCard->isIssuable();

        // Assert
        $this->assertEquals($expectedOutput['apiId'], $apiId);
        $this->assertEquals($expectedOutput['id'], $id);
        $this->assertEquals($expectedOutput['balance'], $balance);
        $this->assertEquals($expectedOutput['currency'], $currency);
        $this->assertEquals($expectedOutput['promotional'], $promotional);
        $this->assertEquals($expectedOutput['created_at'], $created_at);
        $this->assertEquals($expectedOutput['is_redeemable'], $is_redeemable);
        $this->assertEquals($expectedOutput['is_issuable'], $is_issuable);
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
     * @return array<array>
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
                    'is_redeemable' => true,
                    'is_issuable' => false,
                    'created_at' => '2018-09-25T11:25:03+00:00',
                ],
                [
                    'apiId' => 'giftcardCode',
                    'id' => 'giftCardId',
                    'balance' => 1250,
                    'currency' => 'EUR',
                    'promotional' => false,
                    'is_redeemable' => true,
                    'is_issuable' => false,
                    'created_at' => '2018-09-25T11:25:03+00:00',
                ],
            ],
            [
                [
                    'id' => 'giftCardId',
                    'code' => 'giftcardCode',
                    'balance' => null,
                    'currency' => 'EUR',
                    'promotional' => true,
                    'is_redeemable' => false,
                    'is_issuable' => true,
                    'created_at' => '2018-09-25T11:25:03+00:00',
                ],
                [
                    'apiId' => 'giftcardCode',
                    'id' => 'giftCardId',
                    'balance' => null,
                    'currency' => 'EUR',
                    'promotional' => true,
                    'is_redeemable' => false,
                    'is_issuable' => true,
                    'created_at' => '2018-09-25T11:25:03+00:00',
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
                    'is_redeemable' => null,
                    'is_issuable' => null,
                    'created_at' => null,
                ],
            ],
        ];
    }
}
