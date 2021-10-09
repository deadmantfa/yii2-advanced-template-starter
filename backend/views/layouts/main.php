<?php

/** @var View $this */

/** @var string $content */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

AppAsset::register($this);

$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
$setting = Yii::$app->userSetting;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>

    <link rel="manifest" href="/site.webmanifest">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body class="hold-transition
<?= $setting->get('theme|body', Yii::$app->user->id) ?> 
accent-<?= $setting->get('theme|color.body', Yii::$app->user->id) ?? 'primary' ?>">
<?php $this->beginBody() ?>
<div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="<?= Url::to('@web/img/yii3_sign_color.svg') ?>" alt="Yii Framework Logo"
             height="60" width="60">
    </div>
    <?php
    echo $this->render('header.php', ['setting' => $setting]);
    echo $this->render('left.php', ['setting' => $setting]);
    echo $this->render('content.php', ['content' => $content, 'setting' => $setting]);
    ?>
</div>
<?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>
