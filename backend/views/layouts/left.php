<?php

/** @var View $this */

/** @var string $directoryAsset */

use yii\helpers\Html;
use yii\web\View;

?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <?= Html::a('<img class="brand-image img-circle elevation-3" src="' . ($directoryAsset . '/img/AdminLTELogo.png') . '" alt="APP"><span class="brand-text font-weight-light">' . Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'brand-link']) ?>
    <div class="sidebar">

        <nav class="mt-2">
            <?= dmstr\adminlte\widgets\Menu::widget(
                [
                    'options' => ['class' => 'nav nav-pills nav-sidebar flex-column', 'data-widget' => 'treeview'],
                    'items' => [
                        ['label' => 'Menu Yii2', 'header' => true],
                        ['label' => 'Dashboard', 'iconType' => 'far', 'icon' => 'file-code', 'url' => ['/site/index']],
                        [
                            'label' => 'Admin Tools',
                            'icon' => 'share',
                            'url' => '#',
                            'items' => [
                                ['label' => 'User Management', 'iconType' => 'far', 'icon' => 'user', 'url' => ['/user/admin/index'],],
                                ['label' => 'Debug', 'icon' => 'tachometer-alt', 'url' => ['/debug'],],
                                ['label' => 'Gii', 'iconType' => 'far', 'icon' => 'file-code', 'url' => ['/gii'],],
                                ['label' => 'Audit Log', 'iconType' => 'fa', 'icon' => 'chalkboard', 'url' => ['/audit'],],
                            ],
                            'visible' => Yii::$app->user->can('admin')
                        ],
                    ],
                ]
            ) ?>
        </nav>

    </div>

</aside>
