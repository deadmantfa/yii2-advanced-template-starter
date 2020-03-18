<?php

use bedezign\yii2\audit\Audit;
use common\models\User;
use Da\User\Component\AuthDbManagerComponent;
use yii\caching\FileCache;

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
            'class' => FileCache::class,
        ],
        'authManager' => [
            'class' => AuthDbManagerComponent::class,
            'defaultRoles' => ['guest', 'user'],
        ],
    ],
];
