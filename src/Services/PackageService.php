<?php

namespace Gifty\Client\Services;

use Gifty\Client\Resources\Collection;
use Gifty\Client\Resources\Package;
use Gifty\Client\Services\Operation\All;
use Gifty\Client\Services\Operation\Get;

/**
 * Class PackageService
 * @package Gifty\Client\Service
 * @method Package[]|Collection all(array $options = [])
 */
final class PackageService extends AbstractService
{
    use All;
    use Get;

    protected const API_PATH = 'packages';

    protected function getResourceClassPath(): string
    {
        return Package::class;
    }
}
