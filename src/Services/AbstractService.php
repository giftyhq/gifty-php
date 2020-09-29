<?php

namespace Gifty\Client\Services;

use Gifty\Client\Exceptions\ApiException;
use Gifty\Client\HttpClient\GiftyHttpClientInterface;
use Gifty\Client\Resources\AbstractResource;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractService
{
    protected const API_PATH = '/';
    protected const API_PATH_PARENT = null;

    /**
     * @var GiftyHttpClientInterface
     */
    protected $httpClient;

    /**
     * @var AbstractResource|null
     */
    private $parentResource;

    /**
     * GiftCardsService constructor.
     * @param GiftyHttpClientInterface $httpClient
     * @param AbstractResource|null $parentResource
     */
    public function __construct(GiftyHttpClientInterface $httpClient, ?AbstractResource $parentResource = null)
    {
        $this->httpClient = $httpClient;
        $this->parentResource = $parentResource;
    }

    abstract protected function getResourceClassPath(): string;

    /**
     * @param array<string> $arguments
     * @return string
     */
    protected function buildApiPath(array $arguments = []): string
    {
        array_unshift($arguments, $this::API_PATH);

        return $this->buildParentApiPath($arguments);
    }

    /**
     * @param array<string> $arguments
     * @return string
     */
    protected function buildParentApiPath(array $arguments = []): string
    {
        if ($this->parentResource !== null) {
            array_unshift(
                $arguments,
                $this::API_PATH_PARENT,
                $this->parentResource->getApiIdentifier()
            );
        }

        return implode('/', $arguments);
    }

    /**
     * @param ResponseInterface $response
     * @return array<bool|int|string>
     * @throws ApiException
     */
    protected function parseApiResponse(ResponseInterface $response): array
    {
        $jsonString = $response->getBody()->getContents();
        $jsonObject = json_decode($jsonString);

        if ($jsonObject === null) {
            throw new ApiException(
                sprintf('The server responded with error code %s.', $response->getStatusCode()),
                $response->getStatusCode()
            );
        }

        if (property_exists($jsonObject, 'errors')) {
            $error = $jsonObject->errors[0];

            throw new ApiException($error->detail, $error->status);
        }

        return (array)$jsonObject->data;
    }
}
