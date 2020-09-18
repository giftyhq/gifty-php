<?php

namespace Gifty\Client\Resources;

use Gifty\Client\Exceptions\MissingParameterException;
use Gifty\Client\HttpClient\GiftyHttpClientInterface;

abstract class AbstractResource
{
    /**
     * @var string
     */
    protected $apiIdentifierField = 'id';

    /**
     * Associative array for storing property values
     *
     * @var mixed[]
     */
    protected $container = [];

    /**
     * @var GiftyHttpClientInterface
     */
    protected $httpClient;

    /**
     * AbstractResource constructor.
     * @param GiftyHttpClientInterface $httpClient
     * @param array<string|int|bool> $data
     * @throws MissingParameterException
     */
    public function __construct(GiftyHttpClientInterface $httpClient, array $data = [])
    {
        if (isset($data[$this->apiIdentifierField]) === false) {
            throw new MissingParameterException(
                sprintf('The \'%s\' parameter is required.', $this->apiIdentifierField)
            );
        }

        $this->httpClient = $httpClient;
    }

    public function getApiIdentifier(): string
    {
        return $this->container[$this->apiIdentifierField];
    }
}
