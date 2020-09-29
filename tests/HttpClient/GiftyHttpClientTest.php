<?php

namespace Gifty\Client\Tests\HttpClient;

use Gifty\Client\Exceptions\ApiException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Gifty\Client\HttpClient\GiftyHttpClient
 */
class GiftyHttpClientTest extends TestCase
{

    public function testUnauthorizedConnectionSetup(): void
    {
        // Arrange
        $key = 'invalid';
        $gifty = new \Gifty\Client\GiftyClient($key);

        // Assert
        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Unauthenticated.');

        // Act
        $gifty->locations->all();
    }
}
