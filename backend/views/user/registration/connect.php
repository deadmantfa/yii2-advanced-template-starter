<?php

/*
 * This file is part of the 2amigos/yii2-usuario project.
 *
 * (c) 2amigOS! <http://2amigos.us/>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

use Da\User\Model\SocialNetworkAccount;
use Da\User\Model\User;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var User $model
 * @var SocialNetworkAccount $account
 */

$this->title = Yii::t('usuario', 'Sign in');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">
    <div class="card-body login-card-body">
        <p class="login-box-msg">
            <?= Yii::t(
                'usuario',
                'In order to finish your registration, we need you to enter following fields'
            ) ?>:
        </p>
        <?php $form = ActiveForm::begin(
            [
                'id' => $model->formName(),
            ]
        ); ?>

        <?=
        $form
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
            ->textInput(['placeholder' => $model->getAttributeLabel('email')])
        ?>
        <?=
        $form
            ->field(
                $model,
                'username',
                [
                    'inputOptions' => [
                        'autofocus' => 'autofocus',
                        'class' => 'form-control',
                        'tabindex' => '2'
                    ],
                    'wrapperOptions' => [
                        'class' => 'input-group mb-3'
                    ]
                ]
            )
            ->textInput(['placeholder' => $model->getAttributeLabel('username')])
        ?>

        <?= Html::submitButton(Yii::t('usuario', 'Continue'), ['class' => 'btn btn-success btn-block', 'tabindex' => '3']) ?>

        <?php ActiveForm::end(); ?>
    </div>
</div>
<p class="text-center">
    <?= Html::a(
        Yii::t(
            'usuario',
            'If you already registered, sign in and connect this account on settings page'
        ),
        ['/user/settings/networks']
    ) ?>.
</p>
