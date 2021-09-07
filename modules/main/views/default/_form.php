<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\main\models\Diagram;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Diagram */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="diagram-form">

    <?php $form = ActiveForm::begin([
        'id' => $model->isNewRecord ? 'create-diagram-form' : 'update-diagram-form',
        'options' => ['enctype' => 'multipart/form-data']
    ]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 8]) ?>

    <?= $form->field($model, 'type')->dropDownList(Diagram::getTypesArray()) ?>

    <?= $form->field($model, 'status')->dropDownList(Diagram::getStatusesArray()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '<span class="glyphicon glyphicon-floppy-disk"></span> ' .
            Yii::t('app', 'BUTTON_SAVE') : '<span class="glyphicon glyphicon-refresh"></span> ' .
            Yii::t('app', 'BUTTON_UPDATE'), ['class' => 'btn btn-success',
            'name'=>$model->isNewRecord ? 'create-diagram-button' : 'update-diagram-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>