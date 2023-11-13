<?php

/* @var $model_vam app\modules\main\models\VirtualAssistantModel */

use yii\helpers\Html;
use yii\bootstrap5\Modal;
use yii\bootstrap5\ActiveForm;
?>

<?php Modal::begin([
    'id' => 'removeVirtualAssistantModelModalForm',
    'title' => '<h3>' . Yii::t('app', 'VIRTUAL_ASSISTANT_PAGE_DELETE_VIRTUAL_ASSISTANT_MODEL') . '</h3>',
]); ?>

    <div class="modal-body">
        <p style="font-size: 14px">
            <?php echo Yii::t('app', 'VIRTUAL_ASSISTANT_PAGE_MODAL_FORM_MODEL_TEXT'); ?>
        </p>
    </div>

<?php $form = ActiveForm::begin([
    'id' => 'delete-virtual-assistant-model-form',
    'method' => 'post',
    'action' => ['/virtual-assistant/delete-vam/' . $model->id . '/' . $model_vam->id],
    'enableAjaxValidation'=>true,
    'enableClientValidation'=>true,
]); ?>

<?= Html::submitButton('<i class="fa-solid fa-check"></i> ' .
    Yii::t('app', 'BUTTON_DELETE'), ['class' => 'btn btn-danger']) ?>

<button type="button" class="btn btn-primary" style="margin:5px" data-bs-dismiss="modal">
    <?php echo '<i class="fa-solid fa-xmark"></i> ' . Yii::t('app', 'BUTTON_CANCEL')?></button>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>