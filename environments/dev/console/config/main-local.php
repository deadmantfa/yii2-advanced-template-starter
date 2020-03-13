<?php

use yii\gii\Module;

return [
    'bootstrap' => ['gii'],
    'modules' => [
        'gii' => [
            'class' => Module::class,
            'allowedIPs' => ['127.0.0.1', '::1', '192.168.*.*'],
        ]
    ],
];
