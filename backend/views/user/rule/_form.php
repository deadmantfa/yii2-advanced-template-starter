<?php

/**
 * @var $this  yii\web\View
 * @var $model Rule
 */

use Da\User\Model\Rule;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

?>


<?php $form = ActiveForm::begin(
    [
        'enableClientValidation' => false,
        'enableAjaxValidation' => true,
    ]
) ?>

<?= $form->field($model, 'name') ?>

<?= $form->field($model, 'className') ?>

<?= Html::submitButton(Yii::t('usuario', 'Save'), ['class' => 'btn btn-success btn-block']) ?>

<?php ActiveForm::end() ?>
