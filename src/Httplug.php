<?php

/*
 * This file is part of the laravel-httplug Project.
 *
 * (c) laravel-httplug <mathieu.santostefano@gmail.com>
 */

namespace Http\LaravelHttplug;

use Http\Adapter\Guzzle6\Client as Guzzle6Adapter;
use Http\Client\Curl\Client as CurlClient;
use Http\Client\HttpClient;
use Illuminate\Support\Manager;

class Httplug extends Manager
{

    public function createGuzzle6Driver()
    {
        return new Guzzle6Adapter();
    }

    public function createCurlDriver()
    {
        return new CurlClient();
    }

    public function getDefaultDriver()
    {
        // TODO: Return the first configured client or use auto discocery
    }

}
