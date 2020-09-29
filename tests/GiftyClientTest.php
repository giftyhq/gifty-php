<?php

namespace Gifty\Client\Tests;

use Gifty\Client\GiftyClient;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Gifty\Client\GiftyClient
 * @uses   \Gifty\Client\HttpClient\GiftyHttpClient
 */
final class GiftyClientTest extends TestCase
{
    /**
     * @covers \Gifty\Client\GiftyClient
     */
    public function testClientCanBeInitiated(): void
    {
        // Arrange
        $apiKey = 'apiKey';

        // Act
        $client = new GiftyClient($apiKey);

        // Assert
        $this->assertInstanceOf(GiftyClient::class, $client);
    }
}
