<?php

namespace Gifty\Client\Tests\Services;

use Gifty\Client\Resources\Collection;
use Gifty\Client\Resources\Package;
use Gifty\Client\Services\PackageService;
use Gifty\Client\Tests\Common\GiftyMockHttpClient;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Gifty\Client\Services\PackageService
 * @covers \Gifty\Client\Services\AbstractService
 * @uses   \Gifty\Client\Resources\AbstractResource
 * @uses   \Gifty\Client\Resources\Package
 */
final class PackageServiceTest extends TestCase
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
    public function testPackagesAreRetrievable(): void
    {
        // Arrange
        $data = (string) file_get_contents('./tests/Data/Packages/All.json');
        $this->httpClient->mockHandler->append(new Response(200, [], $data));
        $packageService = new PackageService($this->httpClient);

        // Act
        $packages = $packageService->all();

        // Assert
        $this->assertCount(2, $packages);
        $this->assertInstanceOf(Collection::class, $packages);
        $this->assertContainsOnlyInstancesOf(Package::class, $packages);
    }
}
