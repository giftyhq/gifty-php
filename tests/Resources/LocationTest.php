<?php

namespace Gifty\Client\Tests\Resources;

use Gifty\Client\Exceptions\MissingParameterException;
use Gifty\Client\Resources\Location;
use Gifty\Client\Tests\Common\GiftyMockHttpClient;
use PHPUnit\Framework\TestCase;

/**
 * @covers   \Gifty\Client\Resources\Location
 * @covers   \Gifty\Client\Resources\AbstractResource
 */
final class LocationTest extends TestCase
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
     * @dataProvider addressData
     * @param array<bool|int|string> $input
     * @param array<bool|int|string> $expectedOutput
     * @throws MissingParameterException
     */
    public function testLocationParameters(array $input, array $expectedOutput): void
    {
        // Arrange
        $location = new Location($this->httpClient, $input);

        // Act
        $apiId = $location->getApiIdentifier();
        $id = $location->getId();
        $street = $location->getStreet();
        $houseNumber = $location->getHouseNumber();
        $addition = $location->getAddition();
        $postalCode = $location->getPostalCode();
        $city = $location->getCity();

        // Assert
        $this->assertSame($expectedOutput['id'], $apiId);
        $this->assertSame($expectedOutput['id'], $id);
        $this->assertSame($expectedOutput['street'], $street);
        $this->assertSame($expectedOutput['house_number'], $houseNumber);
        $this->assertSame($expectedOutput['addition'], $addition);
        $this->assertSame($expectedOutput['postal_code'], $postalCode);
        $this->assertSame($expectedOutput['city'], $city);
    }

    public function testRequireIdToBeProvided(): void
    {
        // Arrange
        $input = [];

        // Assert
        $this->expectException(MissingParameterException::class);

        // Act
        new Location($this->httpClient, $input);
    }

    /**
     * @return array<array>
     */
    public function addressData(): array
    {
        return [
            [
                [
                    'id' => 'locationId',
                    'street' => 'Floridalaan',
                    'house_number' => '8',
                    'addition' => 'bis',
                    'postal_code' => '3404 WV',
                    'city' => 'IJsselstein',
                ],
                [
                    'id' => 'locationId',
                    'street' => 'Floridalaan',
                    'house_number' => '8',
                    'addition' => 'bis',
                    'postal_code' => '3404 WV',
                    'city' => 'IJsselstein',
                ],
            ],
            [
                [
                    'id' => 'locationId',
                    'street' => 'Floridalaan',
                ],
                [
                    'id' => 'locationId',
                    'street' => 'Floridalaan',
                    'house_number' => null,
                    'addition' => null,
                    'postal_code' => null,
                    'city' => null,
                ],
            ],
        ];
    }
}
