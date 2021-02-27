<?php

use dmstr\adminlte\widgets\Alert;
use yii\bootstrap4\Breadcrumbs;
use yii\helpers\Html;
use yii\helpers\Inflector;

?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <?php if (isset($this->blocks['content-header'])) { ?>
                        <h1><?= $this->blocks['content-header'] ?></h1>
                    <?php } else { ?>
                        <h1>
                            <?php
                            if ($this->title !== null) {
                                echo Html::encode($this->title);
                            } else {
                                echo Inflector::camel2words(
                                    Inflector::id2camel($this->context->module->id)
                                );
                                echo ($this->context->module->id !== Yii::$app->id) ? '<small>Module</small>' : '';
                            } ?>
                        </h1>
                    <?php } ?>
                </div>

                <div class="col-sm-6">
                    <?= Breadcrumbs::widget([
                        'links' => $this->params['breadcrumbs'] ?? [],
                        'options' => [
                            'class' => 'float-sm-right'
                        ]
                    ]) ?>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <?= Alert::widget() ?>
        <?=
        /** @var string $content */
        $content
        ?>
    </section>
</div>

<aside class="control-sidebar control-sidebar-dark" style="display: none;">
</aside>

<footer class="main-footer text-sm">
    <div class="float-right d-none d-sm-block">
        <b>Version</b> 3.0.2
    </div>
    <strong>Copyright © <?= date('Y') ?>&nbsp;<a href="http://adminlte.io">AdminLTE.io</a>.</strong> All rights
    reserved.
</footer>
