<?php

namespace backend\controllers;

use backend\notifications\TestNotification;
use common\models\LoginForm;
use common\models\User;
use Exception;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ErrorAction;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'chat', 'notification'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     * @throws Exception
     */
    public function actionIndex(): string
    {
//        TestNotification::create('test', ['userId' => Yii::$app->user->id])->send();
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
//        TestNotification::create('test', ['userId' => Yii::$app->user->id])->send();
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
