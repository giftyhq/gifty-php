<?php

namespace Gifty\Client\Tests\Services;

use Gifty\Client\Resources\Collection;
use Gifty\Client\Resources\Location;
use Gifty\Client\Services\LocationService;
use Gifty\Client\Tests\Common\GiftyMockHttpClient;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Gifty\Client\Services\LocationService
 * @covers \Gifty\Client\Services\AbstractService
 * @uses   \Gifty\Client\Resources\AbstractResource
 * @uses   \Gifty\Client\Resources\Location
 */
final class LocationServiceTest extends TestCase
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
     * @covers \Gifty\Client\Services\Operation\All
     */
    public function testLocationsAreRetrievable(): void
    {
        // Arrange
        $data = (string) file_get_contents('./tests/Data/Locations/All.json');
        $this->httpClient->mockHandler->append(new Response(200, [], $data));
        $locationService = new LocationService($this->httpClient);

        // Act
        $locations = $locationService->all();

        // Assert
        $this->assertCount(2, $locations);
        $this->assertInstanceOf(Collection::class, $locations);
        $this->assertContainsOnlyInstancesOf(Location::class, $locations);
    }
}
