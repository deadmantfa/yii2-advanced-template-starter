<?php

use bedezign\yii2\audit\Audit;
use bedezign\yii2\audit\components\web\ErrorHandler;
use common\components\RouteAccessControl;
use common\models\User;
use Da\User\Contracts\MailChangeStrategyInterface;
use Da\User\Controller\SecurityController;
use Da\User\Event\FormEvent;
use Da\User\Event\UserEvent;
use Da\User\Module as UserModule;
use justcoded\yii2\rbac\widgets\RbacActiveForm;
use justcoded\yii2\rbac\widgets\RbacGridView;
use kartik\grid\Module as GridModule;
use webzop\notifications\channels\ScreenChannel;
use webzop\notifications\channels\WebChannel;
use webzop\notifications\Module as NotificationModule;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Url;
use yii\i18n\PhpMessageSource;
use yii\log\FileTarget;
use yii\web\JsonParser;
use yii\web\MultipartFormDataParser;
use yii2mod\settings\components\Settings;
use yii2mod\settings\Module as SettingsModule;
use yiier\userSetting\Module as UserSettingModule;
use yiier\userSetting\UserSetting;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'user'],
    'controllerNamespace' => 'backend\controllers',
    'defaultRoute' => '/user/security/login',
    'modules' => [
        'user' => [
            'class' => UserModule::class,
            'maxPasswordAge' => 30,
            'emailChangeStrategy' => MailChangeStrategyInterface::TYPE_SECURE,
            'administrators' => ['deadmantfa'],
            'administratorPermissionName' => 'Master',
            'enableTwoFactorAuthentication' => !YII_DEBUG,
            'enableRegistration' => false,
            'enableSwitchIdentities' => YII_DEBUG,
            'allowAdminPasswordRecovery' => true,
            'classMap' => [
                'User' => User::class,
            ],
            'controllerMap' => [
                'security' => [
                    'class' => SecurityController::class,
                    'on ' . UserEvent::EVENT_AFTER_LOGOUT => static function () {
                        Yii::$app->layout = 'main-login';
                        Yii::$app->setHomeUrl(Url::to(['/user/login']));
                    },
                    'on ' . FormEvent::EVENT_AFTER_LOGIN => static function () {
                        Yii::$app->layout = 'main';
                        Yii::$app->setHomeUrl(Url::to(['/site/index']));
                    },
                ],
            ],
            'mailParams' => [
                'fromEmail' => static function () {
                    return [Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']];
                }
            ],
        ],
        'settings' => [
            'class' => SettingsModule::class,
        ],
        'userSetting' => [
            'class' => UserSettingModule::class,
        ],
        'audit' => [
            'class' => Audit::class,
            'layout' => '@app/views/layouts/main.php',
            'accessRoles' => ['Master']
        ],
        'gridview' => [
            'class' => GridModule::class
            // enter optional module parameters below - only if you need to
            // use your own export download action or custom translation
            // message source
            // 'downloadAction' => 'gridview/export/download',
            // 'i18n' => []
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
                            'publicKey' => 'BNduivdPEyEtVYzPfxU0CEIiZKT3XE_5TQ59CRPsBF2UujsRL6NOWY2p5By3QGpTvsovvz2PK3V9VlXjj1I4jUY',
                            'privateKey' => 'Kw9VixJsDhytceyRsi2krJ3KatN89lqtHqgS_558o78',
                            'reuseVAPIDHeaders' => true
                        ],
                    ],
                ],
            ],
        ],
    ],
    'components' => [
        'userSetting' => [
            'class' => UserSetting::class,
        ],
        'settings' => [
            'class' => Settings::class,
        ],
        'assetManager' => [
            'linkAssets' => true,
        ],
        'request' => [
            'csrfParam' => '_csrf-backend',
            'parsers' => [
                'application/json' => JsonParser::class,
                'multipart/form-data' => MultipartFormDataParser::class
            ],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
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
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'i18n' => [
            'translations' => [
                'yii2mod.settings' => [
                    'class' => PhpMessageSource::class,
                    'basePath' => '@yii2mod/settings/messages',
                ],
            ],
        ],
    ],
    'container' => [
        'definitions' => [
            RbacGridView::class => [
                'class' => RbacGridView::class,
            ],
            RbacActiveForm::class => [
                'class' => ActiveForm::class,
            ],
        ],
    ],
    'as routeAccess' => [
        'class' => RouteAccessControl::class,
        'allowActions' => [
            'user/login',
            'user/security/login',
            'user/registration/resend',
        ],
        'autoCreatePermissions' => true
    ],

    'on beforeRequest' => static function () {
        Yii::$app->layout = Yii::$app->user->isGuest ?
            '@app/views/layouts/main-login.php' :      // or just use 'GuestUser' and
            '@app/views/layouts/main.php';
        Yii::$app->setHomeUrl(Yii::$app->user->isGuest ?
            Url::to('/user/login') :      // or just use 'GuestUser' and
            Url::to('/site/index'));
    },
    'params' => $params,
];
