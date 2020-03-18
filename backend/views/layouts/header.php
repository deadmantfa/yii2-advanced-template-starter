<?php

/** @var View $this */

/** @var string $directoryAsset */

use yii\helpers\Html;
use yii\web\View;

?>

<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!--     Left navbar links-->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
        <!--            <li class="nav-item d-none d-sm-inline-block">-->
        <!--                <a href="/" class="nav-link">Home</a>-->
        <!--            </li>-->
        <!--            <li class="nav-item d-none d-sm-inline-block">-->
        <!--                <a href="#" class="nav-link">Contact</a>-->
        <!--            </li>-->
    </ul>

    <!-- SEARCH FORM -->
    <!--    <form class="form-inline ml-3">-->
    <!--        <div class="input-group input-group-sm">-->
    <!--            <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">-->
    <!--            <div class="input-group-append">-->
    <!--                <button class="btn btn-navbar" type="submit">-->
    <!--                    <i class="fas fa-search"></i>-->
    <!--                </button>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </form>-->

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Messages Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
                <i class="far fa-comments"></i>
                <span class="badge badge-danger navbar-badge">3</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <a href="#" class="dropdown-item">
                    <!-- Message Start -->
                    <div class="media">
                        <?= Html::img(
                            Yii::$app->user->identity->profile->getAvatarUrl(230),
                            [
                                'class' => 'img-rounded img-responsive',
                                'alt' => Yii::$app->user->identity->username,
                            ]
                        ) ?>
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                <?= Yii::$app->user->identity->profile->name ?>
                                <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                            </h3>
                            <p class="text-sm"><?= Yii::$app->user->identity->profile->bio ?></p>
                            <p class="text-sm text-muted"><i
                                        class="far fa-clock mr-1"></i> <?= Yii::$app->user->identity->last_login_at ?>
                            </p>
                        </div>
                    </div>
                    <!-- Message End -->
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <!-- Message Start -->
                    <div class="media">
                        <?= Html::img($directoryAsset . '/img/user8-128x128.jpg', [
                            'alt' => 'User Avatar',
                            'class' => 'img-size-50 img-circle mr-3'
                        ]); ?>
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                John Pierce
                                <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                            </h3>
                            <p class="text-sm">I got your message bro</p>
                            <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                        </div>
                    </div>
                    <!-- Message End -->
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <!-- Message Start -->
                    <div class="media">
                        <?= Html::img($directoryAsset . '/img/user3-128x128.jpg', [
                            'alt' => 'User Avatar',
                            'class' => 'img-size-50 img-circle mr-3'
                        ]); ?>
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                Nora Silvester
                                <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                            </h3>
                            <p class="text-sm">The subject goes here</p>
                            <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                        </div>
                    </div>
                    <!-- Message End -->
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
            </div>
        </li>
        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge">15</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-header">15 Notifications</span>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-envelope mr-2"></i> 4 new messages
                    <span class="float-right text-muted text-sm">3 mins</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-users mr-2"></i> 8 friend requests
                    <span class="float-right text-muted text-sm">12 hours</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-file mr-2"></i> 3 new reports
                    <span class="float-right text-muted text-sm">2 days</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
            </div>
        </li>
        <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                <?= Html::img(
                    Yii::$app->user->identity->profile->getAvatarUrl(160),
                    [
                        'class' => 'user-image img-circle elevation-2',
                        'alt' => Yii::$app->user->identity->username,
                    ]
                ) ?>
                <span class="d-none d-md-inline"><?= Yii::$app->user->identity->profile->name ?></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <!-- User image -->
                <li class="user-header bg-primary">
                    <?= Html::img(
                        Yii::$app->user->identity->profile->getAvatarUrl(160),
                        [
                            'class' => 'img-circle elevation-2',
                            'alt' => Yii::$app->user->identity->username,
                        ]
                    ) ?>

                    <p>
                        <?= Yii::$app->user->identity->profile->name ?>
                        <small><?= Yii::$app->user->identity->sinceAt() ?></small>
                    </p>
                </li>
                <!-- Menu Body -->
                <li class="user-body">
                    <div class="row">
                        <div class="col-4 text-center">
                            <a href="#">Followers</a>
                        </div>
                        <div class="col-4 text-center">
                            <a href="#">Sales</a>
                        </div>
                        <div class="col-4 text-center">
                            <a href="#">Friends</a>
                        </div>
                    </div>
                    <!-- /.row -->
                </li>
                <!-- Menu Footer-->
                <li class="user-footer">
                    <?= Html::a(
                        'Profile',
                        ['/user/settings/profile'],
                        ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                    ) ?>
                    <?= Html::a(
                        'Sign out',
                        ['/user/security/logout'],
                        ['data-method' => 'post', 'class' => 'btn btn-default btn-flat float-right']
                    ) ?>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#"><i class="fas fa-th-large"></i></a>
        </li>
    </ul>
</nav>
