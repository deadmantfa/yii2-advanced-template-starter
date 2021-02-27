<?php

use mirocow\elasticsearch\debug\DebugPanel;
use mirocow\elasticsearch\log\ElasticsearchTarget;
use yii\debug\Module as DebugModule;
use yii\gii\Module as GiiModule;

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
                ],
            ],
        ],
    ],
];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => DebugModule::class,
        'allowedIPs' => ['127.0.0.1', '::1', '192.168.*.*'],
        'panels' => [
            'elasticsearch' => [
                'class' => DebugPanel::class,
            ],
        ],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => GiiModule::class,
        'allowedIPs' => ['127.0.0.1', '::1', '192.168.*.*'],
    ];
}

return $config;
