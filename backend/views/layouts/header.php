<?php

/** @var View $this */

/** @var string $directoryAsset */

use backend\components\NotificationWidget;
use kartik\helpers\Html;
use yii\web\View;

?>

<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!--     Left navbar links-->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Notifications Dropdown Menu -->
        <?php echo NotificationWidget::widget([
            'options' => [
                'class' => 'nav-item dropdown',
                'title' => 'Notifications'
            ],
            'countOptions' => [
                'class' => 'badge badge-warning navbar-badge'
            ]
        ]) ?>

        <li class="nav-item">

            <?php
            $module = Yii::$app->getModule('user');

            if (Yii::$app->session->has($module->switchIdentitySessionKey)) {
                echo Html::a(
                    '<i class="fas fa-quidditch"></i>',
                    ['/user/admin/switch-identity'],
                    ['data-method' => 'post', 'class' => 'nav-link']
                );
            }
            ?>
        </li>
        <li class="nav-item dropdown">
            <?= Html::a(
                Html::icon('sign-out-alt', [], 'fas fa-'),
                ['/user/security/logout'],
                ['data-method' => 'post', 'title' => 'Log out', 'class' => 'nav-link']
            ) ?>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#"><i class="fas fa-th-large"></i></a>
        </li>
    </ul>
</nav>
