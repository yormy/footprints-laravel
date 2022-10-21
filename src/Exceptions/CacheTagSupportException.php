<?php

declare(strict_types=1);

namespace Yormy\LaravelFootsteps\Exceptions;

use Exception;

class CacheTagSupportException extends Exception
{
    public function __construct()
    {
        parent::__construct("GeoIp needs cache that allows tags (use non file and non database or disable GeoIp feature");
    }
}
