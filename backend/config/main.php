<?php

use bedezign\yii2\audit\components\web\ErrorHandler;
use common\models\User;
use Da\User\Contracts\MailChangeStrategyInterface;
use mirocow\elasticsearch\log\ElasticsearchTarget;
use webzop\notifications\channels\ScreenChannel;
use webzop\notifications\channels\WebChannel;
use webzop\notifications\Module as NotificationModule;
use yii\helpers\Url;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'backend\controllers',
    'modules' => [
        'user' => [
            'class' => Da\User\Module::class,
            'maxPasswordAge' => 30,
            'emailChangeStrategy' => MailChangeStrategyInterface::TYPE_SECURE,
            'administrators' => ['deadmantfa'],
            'administratorPermissionName' => 'admin',
            'enableTwoFactorAuthentication' => !YII_DEBUG,
            'enableRegistration' => false,
            'enableSwitchIdentities' => YII_DEBUG,
            'allowAdminPasswordRecovery' => true,
            'classMap' => [
                'User' => User::class,
            ],
        ],
        'audit' => [
            'layout' => '@backend/views/layouts/main',
        ],
        'notifications' => [
            'class' => NotificationModule::class,
            'channels' => [
                'screen' => [
                    'class' => ScreenChannel::class,
                ],
//                'email' => [
//                    'class' => EmailChannel::class,
//                    'message' => [
//                        'from' => 'example@email.com'
//                    ],
//                ],
                'web' => [
                    'class' => WebChannel::class,
                    'enable' => true,
                    'config' => [
                        'serviceWorkerFilepath' => '/web/sw.js',
                        'serviceWorkerScope' => '/',
                        'serviceWorkerUrl' => Url::to('/sw.js'),
                        'subscribeLabel' => 'On',
                        'unsubscribeLabel' => 'Off',
                    ],
                    'auth' => [
                        'VAPID' => [
                            'subject' => 'mailto:me@website.com',
                            'publicKey' => 'BLKSeqxNeairZghlb2QSvdJ5FV25dTVhBPU7Xlm1yobjm3Lohzl47j9E8vZddXqI_uSCBeSV_aHynph66532hzY',
                            'privateKey' => 'UhSr65JIiPorDLPe71XlOasypnqcc3J-aXIXdi-c4bw',
                            'reuseVAPIDHeaders' => true
                        ],
                    ],
                ],
            ],
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => ElasticsearchTarget::class,
                    'levels' => ['error', 'warning'],
                    'index' => 'yii-log',
                    'type' => 'backend',
                ],
            ],
        ],
        'errorHandler' => [
            'class' => ErrorHandler::class,
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<id:\d+>' => 'profile/show',
                '<action:(login|logout)>' => 'security/<action>',
                '<action:(register|resend)>' => 'registration/<action>',
                'confirm/<id:\d+>/<code:[A-Za-z0-9_-]+>' => 'registration/confirm',
                'forgot' => 'recovery/request',
                'recover/<id:\d+>/<code:[A-Za-z0-9_-]+>' => 'recovery/reset',
                'settings/<action:\w+>' => 'settings/<action>',
            ],
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@Da/User/resources/views' => '@app/views/user'
                ]
            ]
        ]
    ],
    'params' => $params,
];
