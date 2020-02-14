<?php

use Da\User\Contracts\MailChangeStrategyInterface;
use yii\web\Response;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'modules' => [
        'user' => [
            'class' => Da\User\Module::class,
            'enableTwoFactorAuthentication' => true,
            'maxPasswordAge' => 30,
            'emailChangeStrategy' => MailChangeStrategyInterface::TYPE_SECURE,
            'administratorPermissionName' => 'admin'
        ],
        'v1' => [
            'basePath' => '@api/modules/v1',
            'class' => api\modules\v1\Module::class
        ]
    ],
    'components' => [
        'request' => [
            'parsers' => [
                'application/json' => yii\web\JsonParser::class,
            ]
        ],
        'response' => [
            // ...
            'formatters' => [
                Response::FORMAT_JSON => [
                    'class' => yii\web\JsonResponseFormatter::class,
                    'prettyPrint' => YII_DEBUG, // use "pretty" output in debug mode
                    'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
                    // ...
                ],
            ],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => mirocow\elasticsearch\log\ElasticsearchTarget::class,
                    'levels' => ['error', 'warning'],
                    'index' => 'yii-log',
                    'type' => 'api',
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/user',
                    'tokens' => [
                        '{id}' => '<id:\\w+>'
                    ]
                ]
            ],
        ]
    ],
    'params' => $params,
];
