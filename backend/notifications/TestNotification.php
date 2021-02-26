<?php

namespace backend\notifications;

use common\models\User;
use Exception;
use webzop\notifications\Notification;

/**
 *
 * @property-read null|array $route
 * @property-read mixed $description
 * @property-read void $title
 */
class TestNotification extends Notification
{
    public array $titleText = [
        'What is Lorem Ipsum?',
        'Why do we use it?',
        'Where does it come from?',
        'Where can I get some?',
        'Lorem Ipsum',
    ];

    public array $descriptionText = [
        'Wikipedia has a recording of a cat meowing, because why not?',
        'Cats make more than 100 different sounds whereas dogs make around 10.',
        'Lorem Ipsum is simply dummy text of the printing and typesetting industry',
        'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout',
        'There are many variations of passages of Lorem Ipsum available'
    ];
    /**
     * @var User the user object
     */
    public User $user;

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function getTitle(): string
    {
        return $this->titleText[random_int(0, 5)];
    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function getDescription(): string
    {
        return $this->descriptionText[random_int(0, 5)];
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
