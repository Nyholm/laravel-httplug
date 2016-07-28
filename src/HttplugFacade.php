<?php

namespace Http\LaravelHttplug;

use Illuminate\Support\Facades\Facade;

class HttplugFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-httplug';
    }
}
