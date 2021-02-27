<?php

use Da\User\Contracts\MailChangeStrategyInterface;
use yii\web\ErrorHandler;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'modules' => [
        'user' => [
            'class' => Da\User\Module::class,
            'enableTwoFactorAuthentication' => true,
            'maxPasswordAge' => 30,
            'emailChangeStrategy' => MailChangeStrategyInterface::TYPE_SECURE,
            'administratorPermissionName' => 'admin',
            'classMap' => [
                'User' => common\models\User::class,
            ],
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'errorHandler' => [
            'class' => ErrorHandler::class,
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
    ],
    'params' => $params,
];
