<?php

use mirocow\elasticsearch\log\ElasticsearchTarget;
use yii\gii\Module;

return [
    'bootstrap' => ['gii', 'log'],

    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => ElasticsearchTarget::class,
                    'levels' => ['error', 'warning'],
                    'index' => 'fdi-app-log',
                    'type' => 'console'
                ],
            ],
        ],
    ],
    'modules' => [
        'gii' => [
            'class' => Module::class,
            'allowedIPs' => ['127.0.0.1', '::1', '192.168.*.*'],
        ]
    ],
];
