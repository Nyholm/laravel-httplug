<?php

return [

    'classes' => [
        # uses discovery if not specified
        'message_factory' => '',
        'uri_factory' => '',
        'stream_factory' => '',
    ],

    'clients' => [
        // The number of clients is configurable by the user
        'acme' => [
            'type' => 'guzzle6',
            'config' => [
                'verify' => false,
                'timeout' => 2,
                # more options to the guzzle 6 constructor
            ],
        ],
        'my_curl' => [
            'type' => 'curl',
            'config' => [

            ],
        ],
    ],
];
