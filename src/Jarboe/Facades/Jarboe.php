<?php

namespace Yaro\Jarboe\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array routeGroupOptions(bool $availableForGuest = false)
 * @method static void crud($uri, $controller)
 */
class Jarboe extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'jarboe';
    }
}
