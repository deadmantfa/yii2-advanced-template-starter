<?php

namespace backend\controllers;

use backend\components\Controller;
use backend\notifications\TestNotification;
use common\models\LoginForm;
use common\models\User;
use Exception;
use Yii;

/**
 * Site controller
 */
class SiteController extends Controller
{

    /**
     * Displays homepage.
     *
     * @return string
     * @throws Exception
     */
    public function actionIndex(): string
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin(): string
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout(): string
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }


    /**
     * Displays Chat.
     *
     * @return string
     * @throws Exception
     */
    public function actionChat(): string
    {
        return $this->render('chat');
    }


    /**
     * Displays Chat.
     *
     * @return string
     * @throws Exception
     */
    public function actionNotification(): string
    {
        if (Yii::$app->request->isAjax) {
            $users = User::find();
            foreach ($users->each() as $user) {
                TestNotification::create('test', ['userId' => $user->id])->send();
            }
        }
        return $this->render('notification');
    }
}
