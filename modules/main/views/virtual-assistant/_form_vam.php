<?php

use app\modules\main\models\Diagram;
use app\modules\main\models\VirtualAssistantModel;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\main\models\VirtualAssistant $model_vam */
/** @var yii\widgets\ActiveForm $form */
?>

<?php $this->registerCssFile('/css/create.css', ['position'=>yii\web\View::POS_HEAD]); ?>

<div class="virtual-assistant-form">

    <?php $form = ActiveForm::begin([
        'id' => 'create-virtual-assistant-modal-form',
        'options' => ['enctype' => 'multipart/form-data']
    ]); ?>

    <?= $form->field($model_vam, 'dialogue_model')->dropDownList(Diagram::getAllStateTransitionDiagramArray()) ?>

    <?= $form->field($model_vam, 'target_model')->dropDownList(Diagram::getAllStateTransitionDiagramArray()) ?>

    <?= $form->field($model_vam, 'type')->dropDownList(VirtualAssistantModel::getTypesArray()) ?>

    <div class="form-group">
        <?= Html::submitButton('<span class="glyphicon glyphicon-floppy-disk"></span> ' .
            Yii::t('app', 'BUTTON_SAVE'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>