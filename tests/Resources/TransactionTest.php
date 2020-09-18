<?php

namespace Gifty\Tests\Resources;

use Gifty\Client\Exceptions\MissingParameterException;
use Gifty\Client\Resources\Transaction;
use Gifty\Tests\Common\TestHelper;
use PHPUnit\Framework\TestCase;

/**
 * @covers   \Gifty\Client\Resources\Transaction
 * @covers   \Gifty\Client\Resources\AbstractResource
 */
final class TransactionTest extends TestCase
{
    use TestHelper;

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
        $this->assertEquals($expectedOutput['id'], $apiId);
        $this->assertEquals($expectedOutput['id'], $id);
        $this->assertEquals($expectedOutput['amount'], $amount);
        $this->assertEquals($expectedOutput['currency'], $currency);
        $this->assertEquals($expectedOutput['status'], $status);
        $this->assertEquals($expectedOutput['type'], $type);
        $this->assertEquals($expectedOutput['description'], $description);
        $this->assertEquals($expectedOutput['captured_at'], $captured_at);
        $this->assertEquals($expectedOutput['created_at'], $created_at);
        $this->assertEquals($expectedOutput['is_capturable'], $is_capturable);
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
                    'amount' => null,
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
