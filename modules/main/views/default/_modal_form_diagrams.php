<?php

/* @var $model app\modules\main\models\Diagram */

use yii\helpers\Html;
use yii\bootstrap5\Modal;
use yii\bootstrap5\Button;
use yii\bootstrap5\ActiveForm;
?>

<?php Modal::begin([
    'id' => 'removeDiagramModalForm',
    'title' => '<h3>' . Yii::t('app', 'DIAGRAMS_PAGE_DELETE_DIAGRAM') . '</h3>',
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

<?= Html::submitButton('<i class="fa-solid fa-check"></i> ' .
    Yii::t('app', 'BUTTON_DELETE'), ['class' => 'btn btn-danger']) ?>

<!-- Теперь не работает
<= Button::widget([
    'label' => '<span class="glyphicon glyphicon-remove"></span> ' .
        Yii::t('app', 'BUTTON_CANCEL'),
    'encodeLabel' => false,
    'options' => [
        'class' => 'btn-primary',
        'style' => 'margin:5px',
        'data-bs-dismiss'=>'modal'
    ]
]); ?>-->

<button type="button" class="btn btn-primary" style="margin:5px" data-bs-dismiss="modal"><?php echo '<i class="fa-solid fa-xmark"></i> ' . Yii::t('app', 'BUTTON_CANCEL')?></button>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>