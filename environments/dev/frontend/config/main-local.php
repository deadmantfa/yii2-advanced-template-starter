<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
    ],
];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'traceLine' => '<a href="phpstorm://open?url={file}&line={line}">{file}:{line}</a>',
        'tracePathMappings' => [
            '/app' => '/home/deadmantfa/Sites/fd-app',
        ],
        'allowedIPs' => ['127.0.0.1', '::1', '192.168.*.*'],
        'panels' => [
            'elasticsearch' => [
                'class' => 'mirocow\\elasticsearch\\debug\\DebugPanel',
            ],
        ],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '192.168.*.*'],
    ];
}

return $config;
