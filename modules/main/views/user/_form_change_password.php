<?php

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\User */
/* @var $form yii\widgets\ActiveForm */

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
?>

<div class="change-password-form">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'password')->textInput(['maxlength' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton('<span class="glyphicon glyphicon-refresh"></span> ' .
                Yii::t('app', 'BUTTON_UPDATE'), ['class' => 'btn btn-success']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>