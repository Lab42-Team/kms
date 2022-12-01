<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use app\modules\main\models\Diagram;
use app\modules\eete\models\TreeDiagram;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Diagram */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
    $this->registerCssFile('/css/create.css', ['position'=>yii\web\View::POS_HEAD]);
?>

<div class="diagram-form">

    <?php $form = ActiveForm::begin([
        'id' => 'update-diagram-form',
        'options' => ['enctype' => 'multipart/form-data']
    ]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 8]) ?>

    <?= $form->field($model, 'status')->dropDownList(Diagram::getStatusesArray()) ?>

    <div class="form-group">
        <?= Html::submitButton( '<span class="glyphicon glyphicon-refresh"></span> ' .
            Yii::t('app', 'BUTTON_UPDATE'), ['class' => 'btn btn-success',
            'name'=>'update-diagram-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>