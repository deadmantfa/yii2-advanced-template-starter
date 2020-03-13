<?php

namespace api\components\identity;

use api\components\models\User;
use chervand\yii2\oauth2\server\models\AccessToken;
use Da\User\Helper\SecurityHelper;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use yii\base\Security;
use yii\web\IdentityInterface;

/**
 *
 * @property mixed $identifier
 * @property mixed $accessTokens
 */
class IdentityClass extends AccessToken implements UserRepositoryInterface, IdentityInterface
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
        if ($user === null) {
            return null;
        }
        $securityHelper = new SecurityHelper(new Security());
        if ($securityHelper->validatePassword($password, $user->password_hash)) {
            return $user;
        }

        return null;
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public function getAuthKey()
    {
        return $this->identifier;
    }

    public function validateAuthKey($authKey)
    {
        return $this->identifier === $authKey;
    }

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->user_id;
    }
}
