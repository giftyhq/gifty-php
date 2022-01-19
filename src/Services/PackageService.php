<?php

namespace Gifty\Client\Services;

use Gifty\Client\Resources\Collection;
use Gifty\Client\Resources\Package;
use Gifty\Client\Services\Operation\All;

/**
 * Class PackageService
 * @package Gifty\Client\Service
 * @method Package[]|Collection all()
 */
final class PackageService extends AbstractService
{
    use All;

    protected const API_PATH = 'packages';

    protected function getResourceClassPath(): string
    {
        return Package::class;
    }
}
