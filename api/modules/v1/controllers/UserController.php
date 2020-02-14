<?php

namespace api\modules\v1\controllers;

use api\components\controllers\ActiveController;

class UserController extends ActiveController
{
    public $modelClass = 'api\modules\v1\models\User';
}
