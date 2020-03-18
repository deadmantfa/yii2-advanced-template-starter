<?php

/*
 * This file is part of the 2amigos/yii2-usuario project.
 *
 * (c) 2amigOS! <http://2amigos.us/>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

use Da\User\Form\ResendForm;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var ResendForm $model
 */

$this->title = Yii::t('usuario', 'Request new confirmation message');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="card">
    <div class="card-body login-card-body">
        <p class="login-box-msg"><?= Html::encode($this->title) ?></p>

        <?php $form = ActiveForm::begin(
            [
                'id' => $model->formName(),
                'enableAjaxValidation' => true,
                'enableClientValidation' => false,
            ]
        ); ?>

        <?= $form
            ->field(
                $model,
                'email',
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
            )
            ->textInput(['autofocus' => true]) ?>

        <?= Html::submitButton(Yii::t('usuario', 'Continue'), ['class' => 'btn btn-primary btn-block', 'tabindex' => '2']) ?>
        <br>

        <?php ActiveForm::end(); ?>
    </div>
</div>
