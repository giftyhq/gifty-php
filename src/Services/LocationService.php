<?php

namespace Gifty\Client\Services;

use Gifty\Client\Resources\Collection;
use Gifty\Client\Resources\Location;
use Gifty\Client\Services\Operation\All;

/**
 * Class LocationService
 * @package Gifty\Client\Service
 * @method Location[]|Collection all(array $options = [])
 */
final class LocationService extends AbstractService
{
    use All;

    protected const API_PATH = 'locations';

    protected function getResourceClassPath(): string
    {
        return Location::class;
    }
}
