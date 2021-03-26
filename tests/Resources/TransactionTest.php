<?php

namespace Gifty\Client\Tests\Resources;

use Gifty\Client\Exceptions\MissingParameterException;
use Gifty\Client\Resources\Transaction;
use Gifty\Client\Tests\Common\GiftyMockHttpClient;
use PHPUnit\Framework\TestCase;

/**
 * @covers   \Gifty\Client\Resources\Transaction
 * @covers   \Gifty\Client\Resources\AbstractResource
 */
final class TransactionTest extends TestCase
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
     * @dataProvider transactionData
     * @param array<bool|int|string> $input
     * @param array<bool|int|string> $expectedOutput
     * @throws MissingParameterException
     */
    public function testTransactionParameters(array $input, array $expectedOutput): void
    {
        // Arrange
        $transaction = new Transaction($this->httpClient, $input);

        // Act
        $apiId = $transaction->getApiIdentifier();
        $id = $transaction->getId();
        $amount = $transaction->getAmount();
        $currency = $transaction->getCurrency();
        $status = $transaction->getStatus();
        $type = $transaction->getType();
        $description = $transaction->getDescription();
        $captured_at = $transaction->getCapturedAt();
        $created_at = $transaction->getCreatedAt();
        $is_capturable = $transaction->isCapturable();

        // Assert
        $this->assertSame($expectedOutput['id'], $apiId);
        $this->assertSame($expectedOutput['id'], $id);
        $this->assertSame($expectedOutput['amount'], $amount);
        $this->assertSame($expectedOutput['currency'], $currency);
        $this->assertSame($expectedOutput['status'], $status);
        $this->assertSame($expectedOutput['type'], $type);
        $this->assertSame($expectedOutput['description'], $description);
        $this->assertSame($expectedOutput['captured_at'], $captured_at);
        $this->assertSame($expectedOutput['created_at'], $created_at);
        $this->assertSame($expectedOutput['is_capturable'], $is_capturable);
    }

    public function testRequireIdToBeProvided(): void
    {
        // Arrange
        $input = [];

        // Assert
        $this->expectException(MissingParameterException::class);

        // Act
        new Transaction($this->httpClient, $input);
    }

    /**
     * @return array<array>
     */
    public function transactionData(): array
    {
        return [
            [
                [
                    'id' => 'transactionId',
                    'amount' => 1250,
                    'currency' => 'EUR',
                    'status' => 'success',
                    'type' => 'issue',
                    'description' => 'issued',
                    'is_capturable' => true,
                    'captured_at' => '2018-11-03T11:41:41+00:00',
                    'created_at' => '2018-11-03T11:41:41+00:00',
                ],
                [
                    'id' => 'transactionId',
                    'amount' => 1250,
                    'currency' => 'EUR',
                    'status' => 'success',
                    'type' => 'issue',
                    'description' => 'issued',
                    'is_capturable' => true,
                    'captured_at' => '2018-11-03T11:41:41+00:00',
                    'created_at' => '2018-11-03T11:41:41+00:00',
                ],
            ],
            [
                [
                    'id' => 'transactionId',
                    'is_capturable' => 'invalid value',
                ],
                [
                    'id' => 'transactionId',
                    'amount' => 0,
                    'currency' => null,
                    'status' => null,
                    'type' => null,
                    'description' => null,
                    'is_capturable' => false,
                    'captured_at' => null,
                    'created_at' => null,
                ],
            ],
        ];
    }
}
