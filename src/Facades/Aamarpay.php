<?php

namespace Shipu\Aamarpay\Facades;

use Illuminate\Support\Facades\Facade;

class Aamarpay extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'aamarpay';
    }
}
