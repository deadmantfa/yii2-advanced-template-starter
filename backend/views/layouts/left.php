<?php

/** @var View $this */

/** @var string $directoryAsset */

use webzop\notifications\widgets\WebNotifications;
use yii\helpers\Html;
use yii\web\View;

?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <?= Html::a('<img class="brand-image img-circle elevation-3" src="' . ($directoryAsset . '/img/AdminLTELogo.png') . '" alt="APP"><span class="brand-text font-weight-light">' . Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'brand-link']) ?>
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <?= Html::img(
                    Yii::$app->user->identity->profile->getAvatarUrl(160),
                    [
                        'class' => 'img-circle elevation-2',
                        'alt' => Yii::$app->user->identity->username,
                    ]
                ) ?>
            </div>
            <div class="info">
                <div class="col">
                    <?= Html::a(
                        Yii::$app->user->identity->profile->name,
                        ['/user/settings/profile'],
                        ['data-method' => 'post', 'class' => 'd-block']
                    ) ?>
                </div>
            </div>
        </div>
        <nav class="mt-2">
            <?= dmstr\adminlte\widgets\Menu::widget(
                [
                    'options' => ['class' => 'nav nav-pills nav-sidebar flex-column', 'data-widget' => 'treeview'],
                    'items' => [
                        ['label' => 'Menu Yii2', 'header' => true],
                        ['label' => 'Dashboard', 'iconType' => 'far', 'icon' => 'file-code', 'url' => ['/site/index']],
                        ['label' => 'Chat', 'iconType' => 'fas', 'icon' => 'comment-alt', 'url' => ['/site/chat']],
                        ['label' => 'Notification', 'iconType' => 'fas', 'icon' => 'bell', 'url' => ['/site/notification']],
                        [
                            'label' => 'Admin Tools',
                            'icon' => 'share',
                            'url' => '#',
                            'items' => [
                                ['label' => 'User Management', 'iconType' => 'far', 'icon' => 'user', 'url' => ['/user/admin/index'],],
                                ['label' => 'RBAC', 'iconType' => 'fas', 'icon' => 'universal-access', 'url' => ['/rbac'],],
                                ['label' => 'Debug', 'icon' => 'tachometer-alt', 'url' => ['/debug'],],
                                ['label' => 'Gii', 'iconType' => 'far', 'icon' => 'file-code', 'url' => ['/gii'],],
                                ['label' => 'Audit Log', 'iconType' => 'fas', 'icon' => 'chart-bar', 'url' => ['/audit'],],
                            ],
                            'visible' => Yii::$app->user->can('Master')
                        ],
                    ],
                ]
            ) ?>
        </nav>
        <div style="position: absolute;bottom: 0;">
            <a href="#" class="text-muted">Web Notification</a>
            <?= WebNotifications::widget([
            'template' => '<button id="js-web-push-subscribe-button" class="btn btn-secondary hide-on-collapse" disabled="disabled"></button>'
            ]) ?>
        </div>
    </div>
</aside>
