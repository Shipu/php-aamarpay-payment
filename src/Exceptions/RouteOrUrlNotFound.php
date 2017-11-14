<?php

namespace Shipu\Aamarpay\Exceptions;

use Exception;

class RouteOrUrlNotFound extends Exception
{
    /**
     * The exception description.
     *
     * @var string
     */
    protected $message = 'This route or url is empty in your config.';
}
