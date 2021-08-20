<?php

use yii\gii\Module;

return [
    'bootstrap' => ['gii', 'log'],
    'modules' => [
        'gii' => [
            'class' => Module::class,
            'allowedIPs' => ['127.0.0.1', '::1', '192.168.*.*'],
        ]
    ],
];
