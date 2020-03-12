<?php


namespace api\components\models;

use common\models\User as BaseUser;
use League\OAuth2\Server\Entities\UserEntityInterface;

class User extends BaseUser implements UserEntityInterface
{

    /**
     * @inheritDoc
     */
    public function getIdentifier()
    {
        return $this->getId();
    }
}
