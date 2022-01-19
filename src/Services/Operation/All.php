<?php

namespace Gifty\Client\Services\Operation;

use Gifty\Client\Resources\AbstractResource;
use Gifty\Client\Resources\Collection;

trait All
{
    /**
     * @return Collection<AbstractResource>
     */
    public function all(): Collection
    {
        $path = $this->buildApiPath();
        $response = $this->httpClient->request('GET', $path);
        $resources = $this->parseApiResponse($response);
        $resourceClass = $this->getResourceClassPath();
        $collection = new Collection();

        foreach ($resources as $key => $object) {
            $collection->append(new $resourceClass($this->httpClient, (array)$object));
        }

        return $collection;
    }
}
