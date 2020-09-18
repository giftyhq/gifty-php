<?php

namespace Gifty\Client\Services;

use Gifty\Client\Exceptions\ApiException;
use Gifty\Client\Resources\GiftCard;

final class GiftCardService extends AbstractService
{
    protected const API_PATH = 'giftcards';

    protected function getResourceClassPath(): string
    {
        return GiftCard::class;
    }

    /**
     * Retrieve a GiftCard from the API.
     * This method adds the GiftCard code to the GiftCard
     * resource, because it is not returned from the API.
     * @param string $id Code of the GiftCard
     * @return GiftCard
     * @throws ApiException
     */
    public function get(string $id): GiftCard
    {
        $path = $this->buildApiPath([$id]);
        $response = $this->httpClient->request('GET', $path);

        $resource = $this->parseApiResponse($response);
        $resource['code'] = $id;
        $resourceClass = $this->getResourceClassPath();

        return new $resourceClass($this->httpClient, (array)$resource);
    }
}
