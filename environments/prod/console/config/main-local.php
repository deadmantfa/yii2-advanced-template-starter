<?php

use mirocow\elasticsearch\log\ElasticsearchTarget;

return [
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => ElasticsearchTarget::class,
                    'levels' => ['error', 'warning'],
                    'index' => 'fdi-app-log',
                    'type' => 'console',
                    'hosts' => ['172.31.19.103:9200']
                ],
            ],
        ],
    ]
];
