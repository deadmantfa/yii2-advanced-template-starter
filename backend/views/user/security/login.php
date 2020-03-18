<?php

use app\models\LoginForm;
use Da\User\Widget\ConnectWidget;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

/** @var View $this */
/** @var LoginForm $model */

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

        <?=
        $form
            ->field(
                $model,
                'login',
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
            ->textInput(['placeholder' => $model->getAttributeLabel('email')]);
        ?>
        <?=
        $form
            ->field(
                $model,
                'password',
                [
                    'inputOptions' => [
                        'class' => 'form-control',
                        'tabindex' => '2'
                    ],
                    'wrapperOptions' => [
                        'class' => 'input-group mb-3'
                    ]
                ]
            )
            ->passwordInput()
            ->label(
                Yii::t('usuario', 'Password')
                . ($module->allowPasswordRecovery ?
                    ' (' . Html::a(
                        Yii::t('usuario', 'Forgot password?'),
                        ['/user/recovery/request'],
                        ['tabindex' => '5']
                    )
                    . ')' : '')
            )
        ?>

        <div class="row">
            <div class="col-8">
                <div class="icheck-primary">
                    <?= $form->field($model, 'rememberMe')->checkbox(['tabindex' => '4']) ?>
                </div>
            </div>

            <div class="col-4">
                <?= Html::submitButton(
                    Yii::t('usuario', 'Sign in'),
                    ['class' => 'btn btn-primary btn-block', 'tabindex' => '3']
                ) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>

<?php if ($module->enableEmailConfirmation): ?>
    <p class="text-center">
        <?= Html::a(
            Yii::t('usuario', 'Didn\'t receive confirmation message?'),
            ['/user/registration/resend']
        ) ?>
    </p>
<?php endif ?>
<?php if ($module->enableRegistration): ?>
    <p class="text-center">
        <?= Html::a(Yii::t('usuario', 'Don\'t have an account? Sign up!'), ['/user/registration/register']) ?>
    </p>
<?php endif ?>
<?= ConnectWidget::widget(
    [
        'baseAuthUrl' => ['/user/security/auth'],
    ]
) ?>
