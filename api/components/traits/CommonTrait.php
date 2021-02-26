<?php


namespace api\components\traits;

use chervand\yii2\oauth2\server\components\AuthMethods\HttpBearerAuth;
use chervand\yii2\oauth2\server\components\AuthMethods\HttpMacAuth;
use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\RateLimiter;

trait CommonTrait
{
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        // remove authentication filter
        unset($behaviors['authenticator'], $behaviors['rateLimiter']);
        $auth = Yii::$app->getModule('oauth2');

        // re-add authentication filter
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::class,
            'authMethods' => [
                [
                    'class' => HttpMacAuth::class,
                    'publicKey' => $auth->publicKey,
                    'cache' => $auth->cache,
                ],
                [
                    'class' => HttpBearerAuth::class,
                    'publicKey' => $auth->publicKey,
                    'cache' => $auth->cache,
                ],
            ]
        ];
        $behaviors['rateLimiter'] = [
            'class' => RateLimiter::class,
        ];

        return $behaviors;
    }
}
