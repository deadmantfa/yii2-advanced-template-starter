<?php

use bedezign\yii2\audit\Audit;
use common\models\User;
use Da\User\Component\AuthDbManagerComponent;
use yii\caching\FileCache;
use yii\web\AssetConverter;

return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(__DIR__, 2) . '/vendor',
    'modules' => [
        'audit' => [
            'class' => Audit::class,
            'accessRoles' => ['admin'],
            'compressData' => true,
            'userIdentifierCallback' => [User::class, 'userIdentifierCallback'],
            'userFilterCallback' => [User::class, 'filterByUserIdentifierCallback'],
            'ignoreActions' => ['audit/*', 'debug/*'],
            'maxAge' => 'debug',
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
        'assetManager' => [
            'converter' => [
                'class' => AssetConverter::class,
                'commands' => [
                    'scss' => ['css', '/home/vagrant/.npm-global/bin/sass {from} {to}']
                ],
            ],
        ],
    ],
];
