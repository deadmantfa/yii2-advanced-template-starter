<?php

/*
 * This file is part of the 2amigos/yii2-usuario project.
 *
 * (c) 2amigOS! <http://2amigos.us/>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

use Da\User\Form\LoginForm;
use Da\User\Module;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var LoginForm $model
 * @var Module $module
 */

$this->title = Yii::t('usuario', 'Sign in');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('/shared/_alert', ['module' => Yii::$app->getModule('user')]) ?>


<div class="card">
    <div class="card-body login-card-body">
        <p class="login-box-msg"><?= Html::encode($this->title) ?></p>

        <?php $form = ActiveForm::begin(
            [
                'id' => $model->formName(),
                'enableAjaxValidation' => true,
                'enableClientValidation' => false,
                'validateOnBlur' => false,
                'validateOnType' => false,
                'validateOnChange' => false,
            ]
        ) ?>
        <?= $form->field(
            $model,
            'twoFactorAuthenticationCode',

            [
                'inputOptions' => [
                    'autofocus' => 'autofocus',
                    'class' => 'form-control',
                    'tabindex' => '1'
                ],
                'wrapperOptions' => [
                    'class' => 'input-group mb-3'
                ]
            ]
        ) ?>

        <div class="row">
            <div class="col-md-6">
                <?= Html::a(
                    Yii::t('usuario', 'Cancel'),
                    ['login'],
                    ['class' => 'btn btn-default btn-block', 'tabindex' => '3']
                ) ?>
            </div>
            <div class="col-md-6">
                <?= Html::submitButton(
                    Yii::t('usuario', 'Confirm'),
                    ['class' => 'btn btn-primary btn-block', 'tabindex' => '3']
                ) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
