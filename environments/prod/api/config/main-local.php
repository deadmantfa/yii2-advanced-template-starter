<?php

use mirocow\elasticsearch\log\ElasticsearchTarget;

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => ElasticsearchTarget::class,
                    'levels' => ['error', 'warning'],
                    'index' => 'fdi-app-log',
                    'type' => 'api',
                    'hosts' => ['172.31.19.103:9200']
                ],
            ],
        ],
    ],
];
