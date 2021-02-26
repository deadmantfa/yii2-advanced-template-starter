<?php

use yii\web\User;

return [
    'id' => 'app-common-tests',
    'basePath' => dirname(__DIR__),
    'components' => [
        'user' => [
            'class' => User::class,
            'identityClass' => \common\models\User::class,
        ],
    ],
];
