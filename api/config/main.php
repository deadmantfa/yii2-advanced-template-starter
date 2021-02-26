<?php

use api\components\identity\IdentityClass;
use api\components\models\User;
use chervand\yii2\oauth2\server\components\Grant\RevokeGrant;
use chervand\yii2\oauth2\server\Module;
use Da\User\Contracts\MailChangeStrategyInterface;
use Defuse\Crypto\Key;
use League\OAuth2\Server\Grant\ClientCredentialsGrant;
use League\OAuth2\Server\Grant\ImplicitGrant;
use League\OAuth2\Server\Grant\PasswordGrant;
use League\OAuth2\Server\Grant\RefreshTokenGrant;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use yii\caching\FileDependency;
use yii\rest\UrlRule;
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
    'bootstrap' => ['oauth2', 'log'],
    'modules' => [
        'oauth2' => [
            'class' => Module::class,
            'privateKey' => __DIR__ . '/../oauth2/private.key',
            'publicKey' => __DIR__ . '/../oauth2/public.key',
            'encryptionKey' => Key::loadFromAsciiSafeString('def0000095a6a564c9495753bb18ba79d1131a180ebf8dcde183c8df80d60dd2c13e3d9f04441c958e5e9fd8b87357305cf5b1cd51a2a1c1364958ac71ca0b18bd15cdc9'),
            'cache' => [
                AccessTokenRepositoryInterface::class => [
                    'cacheDuration' => 3600,
                    'cacheDependency' => new FileDependency(['fileName' => 'AccessTokenRepositoryInterface.txt']),
                ],
                RefreshTokenRepositoryInterface::class => [
                    'cacheDuration' => 3600,
                    'cacheDependency' => new FileDependency(['fileName' => 'RefreshTokenRepositoryInterface.txt']),
                ],
            ],
            'enableGrantTypes' => static function (Module &$module) {
                $server = $module->authorizationServer;
                $server->enableGrantType(new ImplicitGrant(
                    new DateInterval('PT1H')
                ));
                $server->enableGrantType(new PasswordGrant(
                    $module->userRepository,
                    $module->refreshTokenRepository
                ));
                $server->enableGrantType(new ClientCredentialsGrant());
                $server->enableGrantType(new RefreshTokenGrant(
                    $module->refreshTokenRepository
                ));
                $server->enableGrantType(new RevokeGrant(
                    $module->refreshTokenRepository,
                    $module->publicKey
                ));
            },
        ],
        'user' => [
            'class' => Da\User\Module::class,
            'enableTwoFactorAuthentication' => true,
            'maxPasswordAge' => 30,
            'emailChangeStrategy' => MailChangeStrategyInterface::TYPE_SECURE,
            'administratorPermissionName' => 'admin',
            'classMap' => [
                'User' => User::class,
            ],
        ],
        'v1' => [
            'basePath' => '@api/modules/v1',
            'class' => api\modules\v1\Module::class
        ]
    ],
    'components' => [
        'user' => [
            'identityClass' => IdentityClass::class,
            // ...
        ],
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
            'name' => 'advanced-api',
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
                    'class' => UrlRule::class,
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
