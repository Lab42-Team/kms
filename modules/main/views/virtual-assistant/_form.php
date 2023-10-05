<?php

use app\modules\main\models\Diagram;
use app\modules\main\models\VirtualAssistant;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\main\models\VirtualAssistant $model */
/** @var yii\widgets\ActiveForm $form */
?>

<?php $this->registerCssFile('/css/create.css', ['position'=>yii\web\View::POS_HEAD]); ?>

<div class="virtual-assistant-form">

    <?php $form = ActiveForm::begin([
        'id' => 'create-virtual-assistant-form',
        'options' => ['enctype' => 'multipart/form-data']
    ]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList(VirtualAssistant::getStatusesArray()) ?>

    <?= $form->field($model, 'dialogue_model')->dropDownList(Diagram::getAllStateTransitionDiagramArray()) ?>

    <?= $form->field($model, 'knowledge_base_model')->dropDownList(Diagram::getAllStateTransitionDiagramArray()) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 8]) ?>

    <div class="form-group">
        <?= Html::submitButton('<span class="glyphicon glyphicon-floppy-disk"></span> ' .
            Yii::t('app', 'BUTTON_SAVE'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
