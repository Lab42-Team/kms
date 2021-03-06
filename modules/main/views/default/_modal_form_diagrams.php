<?php

/* @var $model app\modules\main\models\Diagram */

use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\bootstrap\Button;
use yii\widgets\ActiveForm;
?>

<?php Modal::begin([
    'id' => 'removeDiagramModalForm',
    'header' => '<h3>' . Yii::t('app', 'DIAGRAMS_PAGE_DELETE_DIAGRAM') . '</h3>',
]); ?>

    <div class="modal-body">
        <p style="font-size: 14px">
            <?php echo Yii::t('app', 'DIAGRAMS_PAGE_MODAL_FORM_TEXT'); ?>
        </p>
    </div>

<?php $form = ActiveForm::begin([
    'id' => 'delete-diagram-form',
    'method' => 'post',
    'action' => ['/delete/' . $model->id],
    'enableAjaxValidation'=>true,
    'enableClientValidation'=>true,
]); ?>

<?= Html::submitButton('<span class="glyphicon glyphicon-ok"></span> ' .
    Yii::t('app', 'BUTTON_DELETE'), ['class' => 'btn btn-danger']) ?>

<?= Button::widget([
    'label' => '<span class="glyphicon glyphicon-remove"></span> ' .
        Yii::t('app', 'BUTTON_CANCEL'),
    'encodeLabel' => false,
    'options' => [
        'class' => 'btn-primary',
        'style' => 'margin:5px',
        'data-dismiss'=>'modal'
    ]
]); ?>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>