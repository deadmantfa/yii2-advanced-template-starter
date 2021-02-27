<?php

use backend\assets\AdminLtePluginAsset;
use dmstr\adminlte\widgets\Alert;
use yii\helpers\Html;
use yii\web\View;

/** @var View $this */
/** @var string $content */

AdminLtePluginAsset::register($this);
dmstr\adminlte\web\FontAwesomeAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body class="login-page">

<?php $this->beginBody() ?>

<div class="login-box">
    <div class="login-logo">
        <?= Html::a('<b>Admin</b>LTE', ['/user/login']) ?>
    </div>

    <?= Alert::widget() ?>

    <?= $content ?>
</div>

<?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>
