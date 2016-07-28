<?php

namespace Http\LaravelHttplug;

use Http\Adapter\Guzzle6\Client as Guzzle6Adapter;
use Http\Client\Curl\Client as CurlClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Discovery\StreamFactoryDiscovery;
use Http\Discovery\UriFactoryDiscovery;
use Http\Client\HttpClient;
use Http\Message\MessageFactory;
use Http\Message\StreamFactory;
use Http\Message\UriFactory;
use Illuminate\Support\Manager;

class HttplugManager extends Manager
{
    /**
     * Factories by type.
     *
     * @var array
     */
    private static $factoryClasses = [
        'message_factory' => MessageFactoryDiscovery::class,
        'uri_factory' => UriFactoryDiscovery::class,
        'stream_factory' => StreamFactoryDiscovery::class,
    ];

    public static function client($name)
    {
        $config = config('laravel-httplug');

        if (!isset($config['client'][$name])) {
            throw new \InvalidArgumentException(sprintf('No client configured with name "%s".', $name));
        }

        $clientConfig = $config['client'][$name]['config'];
        $class = self::getClassFromType($config['client'][$name]['type']);

        return new $class($clientConfig);
    }

    /**
     * @return MessageFactory
     */
    public static function messageFactory()
    {
        return self::getService('message_factory');
    }

    /**
     * @return UriFactory
     */
    public static function uriFactory()
    {
        return self::getService('uri_factory');
    }

    /**
     * @return StreamFactory
     */
    public static function streamFactory()
    {
        return self::getService('stream_factory');
    }

    /**
     * @param $service
     *
     * @return mixed
     */
    private static function getService($service)
    {
        $config = config('laravel-httplug');
        if (!empty($config['classes'][$service])) {
            $class = $config['classes'][$service];

            return new $class();
        } else {
            // Find by auto discovery
            $factoryClass = self::$factoryClasses[$service];

            return $factoryClass::find();
        }
    }

    /**
     * Get a client form auto discovery.
     *
     * @return HttpClient
     */
    public function getDefaultDriver()
    {
        return HttpClientDiscovery::find();
    }

    /**
     * @param string $type
     *
     * @return string class name
     */
    private static function getClassFromType($type)
    {
        switch ($type) {
            case 'guzzle6':
                return Guzzle6Adapter::class;
            case 'curl':
                return CurlClient::class;
            default:
                throw new \InvalidArgumentException(sprintf('Type "%s" is not a valid type form HTTPlug', $type));
        }
    }
}
