<?php

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\web\View;

/** @var View $this */
/** @var string $content */
$spl = [
    'login',
    'register',
    'resend',
    'request',
    'reset',
    'connect',
];

$this->registerCssFile('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=swap');
if (in_array(Yii::$app->controller->action->id, $spl)) {
    /**
     * Do not use this code in your template. Remove it.
     * Instead, use the code  $this->layout = '//main-login'; in your controller.
     */
    echo $this->render(
        'main-login',
        ['content' => $content]
    );
} else {

    AppAsset::register($this);

    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
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

    <body class="hold-transition skin-blue sidebar-mini layout-fixed" style="height: auto;">
    <?php $this->beginBody() ?>
    <div class="wrapper">

        <?= $this->render(
            'header.php'
        ) ?>

        <?= $this->render(
            'left.php',
            ['directoryAsset' => $directoryAsset]
        )
        ?>

        <?= $this->render(
            'content.php',
            ['content' => $content]
        ) ?>

    </div>

    <?php $this->endBody() ?>
    </body>

    </html>
    <?php $this->endPage() ?>
<?php } ?>
