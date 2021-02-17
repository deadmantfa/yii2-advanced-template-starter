<?php

namespace backend\notifications;

use common\models\User;
use webzop\notifications\Notification;
use Yii;

/**
 *
 * @property-read null|array $route
 * @property-read mixed $description
 * @property-read void $title
 */
class TestNotification extends Notification
{

    /**
     * @var User the user object
     */
    public User $user;

    /**
     * @inheritdoc
     */
    public function getTitle(): string
    {
        return Yii::t('app', 'Test Title');
    }

    public function getDescription(): ?string
    {
        return Yii::t('app', 'This is a test notification');
    }

    public function getData(): array
    {
        return array_merge_recursive(parent::getData(), ['icon' => 'https://picsum.photos/200']);
    }

    /**
     * @inheritdoc
     */
    public function getRoute(): ?array
    {
        return ['/user/settings/profile'];
    }
}
