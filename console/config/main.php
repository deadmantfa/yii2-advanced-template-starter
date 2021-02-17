<?php

use bedezign\yii2\audit\components\console\ErrorHandler;
use kartik\tree\Module;
use mirocow\elasticsearch\log\ElasticsearchTarget;
use yii\console\controllers\FixtureController;
use yii\console\controllers\MigrateController;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'console\controllers',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'modules' => [
        'user' => [
            'class' => Da\User\Module::class,
        ],
        'gridview' => [
            'class' => \kartik\grid\Module::class,
            // see settings on http://demos.krajee.com/grid#module
        ],
        'datecontrol' => [
            'class' => \kartik\datecontrol\Module::class,
            // see settings on http://demos.krajee.com/datecontrol#module
        ],
        // If you use tree table
        'treemanager' => [
            'class' => Module::class,
            // see settings on http://demos.krajee.com/tree-manager#module
        ],
    ],
    'controllerMap' => [
        'fixture' => [
            'class' => FixtureController::class,
            'namespace' => 'common\fixtures',
        ],
        'migrate' => [
            'class' => MigrateController::class,
            'migrationPath' => [
                '@app/migrations',
                '@yii/rbac/migrations'
            ],
            'migrationNamespaces' => [
                'Da\User\Migration',
                'bedezign\yii2\audit\migrations',
            ],
        ],
    ],
    'components' => [
        'errorHandler' => [
            // console error handler
            'class' => ErrorHandler::class,
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'baseUrl' => 'https://front.fd.test/',
            'rules' => [
            ],
        ],
        'log' => [
            'targets' => [
                [
                    'class' => ElasticsearchTarget::class,
                    'levels' => ['error', 'warning'],
                    'index' => 'yii-log',
                    'type' => 'console',
                ],
            ],
        ],
    ],
    'params' => $params,
];
