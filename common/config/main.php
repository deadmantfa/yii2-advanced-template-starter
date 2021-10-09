<?php

use justcoded\yii2\rbac\components\DbManager;
use justcoded\yii2\rbac\Module;
use yii\caching\FileCache;
use yii\web\AssetConverter;

return [
    'version' => '1.3.0',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(__DIR__, 2) . '/vendor',
    'modules' => [
        'rbac' => [
            'class' => Module::class
        ],
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
            'class' => DbManager::class,
            'defaultRoles' => ['Guest'],
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
