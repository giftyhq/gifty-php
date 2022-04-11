<?php

namespace Gifty\Client\Tests\Resources;

use Gifty\Client\Exceptions\MissingParameterException;
use Gifty\Client\Resources\Package;
use Gifty\Client\Tests\Common\GiftyMockHttpClient;
use PHPUnit\Framework\TestCase;

/**
 * @covers   \Gifty\Client\Resources\Package
 * @covers   \Gifty\Client\Resources\AbstractResource
 */
final class PackageTest extends TestCase
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
     * @dataProvider packageData
     * @param array<bool|int|string> $input
     * @param array<bool|int|string> $expectedOutput
     * @throws MissingParameterException
     */
    public function testPackageParameters(array $input, array $expectedOutput): void
    {
        // Arrange
        $package = new Package($this->httpClient, $input);

        // Act
        $apiId = $package->getApiIdentifier();
        $id = $package->getId();
        $title = $package->getTitle();
        $description = $package->getDescription();
        $amount = $package->getAmount();
        $currency = $package->getCurrency();
        $active = $package->getActive();

        // Assert
        $this->assertSame($expectedOutput['id'], $apiId);
        $this->assertSame($expectedOutput['id'], $id);
        $this->assertSame($expectedOutput['title'], $title);
        $this->assertSame($expectedOutput['description'], $description);
        $this->assertSame($expectedOutput['amount'], $amount);
        $this->assertSame($expectedOutput['currency'], $currency);
        $this->assertSame($expectedOutput['active'], $active);
    }

    public function testRequireIdToBeProvided(): void
    {
        // Arrange
        $input = [];

        // Assert
        $this->expectException(MissingParameterException::class);

        // Act
        new Package($this->httpClient, $input);
    }

    /**
     * @return array<int, array<int, array<string, bool|int|string|null>>>
     */
    public function packageData(): array
    {
        return [
            [
                [
                    'id' => 'packageId',
                    'title' => 'Lunch for 2',
                    'description' => 'Lunch for 2, drinks excluded.',
                    'amount' => 5950,
                    'currency' => 'EUR',
                    'active' => true,
                ],
                [
                    'id' => 'packageId',
                    'title' => 'Lunch for 2',
                    'description' => 'Lunch for 2, drinks excluded.',
                    'amount' => 5950,
                    'currency' => 'EUR',
                    'active' => true,
                ],
            ],
            [
                [
                    'id' => 'packageId',
                    'title' => 'Lunch for 2',
                ],
                [
                    'id' => 'packageId',
                    'title' => 'Lunch for 2',
                    'description' => null,
                    'amount' => 0,
                    'currency' => null,
                    'active' => false,
                ],
            ],
        ];
    }
}
