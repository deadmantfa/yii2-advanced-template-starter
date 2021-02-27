<?php

namespace backend\components;

use yii\filters\VerbFilter;
use yii\web\Controller as YiiController;
use yii\web\ErrorAction;

class Controller extends YiiController
{
    public function actions(): array
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
            ],
        ];
    }


    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
}
