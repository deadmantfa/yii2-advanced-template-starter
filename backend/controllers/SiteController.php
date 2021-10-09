<?php

namespace backend\controllers;

use backend\components\Controller;
use backend\notifications\TestNotification;
use common\models\LoginForm;
use common\models\User;
use Exception;
use Yii;
use yii\web\Response;

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
     * @return Response
     */
    public function actionLogout(): Response
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

    /**
     */
    public function actionSettings(): bool
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $setting = Yii::$app->userSetting;
        $request = Yii::$app->request;
        $key = $request->post('key');
        $value = $request->post('value');
        $state = $request->post('state');
        $userId = Yii::$app->user->id;
        if ($state === null) {
            $setting->set($key, $value, $userId);
            return true;
        }
        if (!$state && $setting->has($key, $userId)) {
            $oldSetting = $setting->get($key, $userId);
            $newSetting = trim(str_replace($value, '', $oldSetting));
            if ($newSetting === '') {
                $setting->remove($key, $userId);
            } else {
                $setting->set($key, $newSetting, $userId);
            }
        } elseif ($state && $setting->has($key, $userId)) {
            $oldSetting = $setting->get($key, $userId);
            $newSetting = $oldSetting . ' ' . $value;
            $setting->set($key, $newSetting, $userId);
        } else {
            $setting->set($key, $value, $userId);
        }
        return true;
    }
}
