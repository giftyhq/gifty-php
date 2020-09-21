<?php

namespace Gifty\Client\Tests\Common;

trait TestHelper
{
    /**
     * @var GiftyMockHttpClient
     */
    protected $httpClient;

    protected function setUp(): void
    {
        $this->httpClient = new GiftyMockHttpClient('https://api.gifty.nl/v1');

        parent::setUp();
    }
}
