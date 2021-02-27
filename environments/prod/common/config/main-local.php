<?php

use bedezign\yii2\audit\Audit;
use common\models\User;
use yii\swiftmailer\Mailer;

return [
    'modules' => [
        'audit' => [
            'class' => Audit::class,
            'accessRoles' => ['Administrator'],
            'compressData' => true,
            'userIdentifierCallback' => [User::class, 'userIdentifierCallback'],
            'userFilterCallback' => [User::class, 'filterByUserIdentifierCallback'],
            'ignoreActions' => ['audit/*', 'debug/*'],
            'maxAge' => 'debug',
        ],
    ],
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=yii2advanced',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => Mailer::class,
            'viewPath' => '@common/mail',
        ],
    ],
];
