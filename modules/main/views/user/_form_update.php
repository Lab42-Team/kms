<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use app\modules\main\models\User;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="update-user-form">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'role')->dropDownList(User::getRoles()) ?>

        <?= $form->field($model, 'status')->dropDownList(User::getStatuses()) ?>

        <?= $form->field($model, 'full_name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton('<span class="glyphicon glyphicon-refresh"></span> ' .
                Yii::t('app', 'BUTTON_UPDATE'), ['class' => 'btn btn-success']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>