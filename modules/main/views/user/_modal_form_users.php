<?php

/* @var $model app\modules\main\models\Diagram */

use yii\helpers\Html;
use yii\bootstrap5\Modal;
use yii\bootstrap5\Button;
use yii\bootstrap5\ActiveForm;
?>

<?php Modal::begin([
    'id' => 'removeUserModalForm',
    'title' => '<h3>' . Yii::t('app', 'USERS_PAGE_DELETE_USER') . '</h3>',
]); ?>

    <div class="modal-body">
        <p style="font-size: 14px">
            <?php echo Yii::t('app', 'USERS_PAGE_MODAL_FORM_TEXT'); ?>
        </p>
    </div>

<?php $form = ActiveForm::begin([
    'id' => 'delete-user-form',
    'method' => 'post',
    'action' => ['/user/delete/' . $model->id],
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