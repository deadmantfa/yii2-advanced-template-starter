<?php

namespace api\modules\v1\controllers;

use api\components\controllers\ActiveController;
use api\components\models\User;

class UserController extends ActiveController
{
    public $modelClass = User::class;
}
