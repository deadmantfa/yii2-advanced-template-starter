<?php

namespace api\components\identity;

use api\components\models\User;
use chervand\yii2\oauth2\server\models\AccessToken;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;

/**
 *
 * @property mixed $identifier
 * @property mixed $accessTokens
 */
class IdentityClass extends AccessToken implements UserRepositoryInterface
{

    public static function findIdentityByAccessToken($token, $type = null)
    {
        $token = static::find()->where(['identifier' => $token])->one();
        if ($token) {
            return $token->user;
        }
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getUserEntityByUserCredentials($username, $password, $grantType, ClientEntityInterface $clientEntity)
    {
        // TODO: Implement getUserEntityByUserCredentials() method.
        $user = User::findOne(['username' => $username]);
        return $user;
        if ($this->securityHelper->validatePassword($password, $user->password_hash)) {
            return $user;
        }

        return null;
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

}
