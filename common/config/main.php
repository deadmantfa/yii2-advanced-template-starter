<?php

use bedezign\yii2\audit\Audit;
use common\models\User;

return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'modules' => [
        'audit' => [
            'class' => Audit::class,
            'accessRoles' => ['admin'],
            'compressData' => true,
            'userIdentifierCallback' => [User::class, 'userIdentifierCallback'],
            'userFilterCallback' => [User::class, 'filterByUserIdentifierCallback'],
        ]
    ],
    'container' => [
        'definitions' => [
            Da\User\Model\User::class => common\models\User::class,
        ],
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['guest', 'user'],
        ],
    ],
];
