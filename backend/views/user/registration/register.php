<?php

/*
 * This file is part of the 2amigos/yii2-usuario project.
 *
 * (c) 2amigOS! <http://2amigos.us/>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

use Da\User\Form\RegistrationForm;
use Da\User\Model\User;
use Da\User\Module;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var RegistrationForm $model
 * @var User $user
 * @var Module $module
 */

$this->title = Yii::t('usuario', 'Sign up');
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

        <?= $form
            ->field(
                $model,
                'username',
                [
                    'inputOptions' => [
                        'class' => 'form-control',
                        'tabindex' => '2'
                    ],
                    'wrapperOptions' => [
                        'class' => 'input-group mb-3'
                    ]
                ]
            ) ?>

        <?php if ($module->generatePasswords === false): ?>
            <?= $form
                ->field(
                    $model,
                    'password',
                    [
                        'inputOptions' => [
                            'class' => 'form-control',
                            'tabindex' => '3'
                        ],
                        'wrapperOptions' => [
                            'class' => 'input-group mb-3'
                        ]
                    ]
                )->passwordInput() ?>
        <?php endif ?>

        <?php if ($module->enableGdprCompliance): ?>
            <?= $form
                ->field(
                    $model,
                    'gdpr_consent',
                    [
                        'inputOptions' => [
                            'class' => 'form-control',
                            'tabindex' => '4'
                        ],
                    ]
                )->checkbox(['value' => 1]) ?>
        <?php endif ?>

        <?= Html::submitButton(Yii::t('usuario', 'Sign up'), ['class' => 'btn btn-success btn-block', 'tabindex' => '5']) ?>

        <?php ActiveForm::end(); ?>
    </div>
</div>
<p class="text-center">
    <?= Html::a(Yii::t('usuario', 'Already registered? Sign in!'), ['/user/security/login']) ?>
</p>
