<?php

namespace common\models;

use Da\User\Model\User as BaseUser;

class User extends BaseUser
{
    /**
     * @param string $id user_id from audit_entry table
     * @return mixed|string
     */
    public static function userIdentifierCallback($id)
    {
        $user = self::findOne($id);
        return $user ? Html::a($user->username, ['/user/admin/update', 'id' => $user->id]) : $id;
    }

    /**
     * @param string $identifier user_id from audit_entry table
     * @return mixed|string
     */
    public static function filterByUserIdentifierCallback($identifier)
    {
        return static::find()->select('id')
            ->where(['like', 'username', $identifier])
            ->orWhere(['like', 'email', $identifier])
            ->column();
    }
}
