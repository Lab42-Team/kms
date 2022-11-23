<?php

use yii\widgets\ActiveForm;
use yii\bootstrap5\Modal;
use yii\bootstrap5\Button;

/* @var $array_levels app\modules\eete\controllers\TreeDiagramsController */

?>

<!-- Модальное окно для вывода сообщений об ошибках при связывании элементов -->
<?php Modal::begin([
    'id' => 'viewMessageErrorLinkingItemsModalForm',
    'title' => '<h3>' . Yii::t('app', 'ERROR_LINKING_ITEMS') . '</h3>',
]); ?>

<div class="modal-body">
    <p id="message-text" style="font-size: 14px">
    </p>
</div>

<?php $form = ActiveForm::begin([
    'id' => 'view-message-modal-form',
]); ?>

<!-- Теперь не работает
<= Button::widget([
    'label' => Yii::t('app', 'BUTTON_OK'),
    'options' => [
        'class' => 'btn-success',
        'style' => 'margin:5px',
        'data-dismiss'=>'modal'
    ]
]); ?>
-->

<button type="button" class="btn btn-success" data-bs-dismiss="modal"><?php echo Yii::t('app', 'BUTTON_OK')?></button>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>



<!-- Модальное окно для вывода сообщений об ошибках при проверке диаграммы -->
<?php Modal::begin([
    'id' => 'viewMessageErrorsWhenCheckingTheChartModalForm',
    'title' => '<h3>' . Yii::t('app', 'VALIDATION') . '</h3>',
]); ?>

<div class="modal-body">
    <div id="message-verification-text">
    </div>
</div>

<?php $form = ActiveForm::begin([
    'id' => 'view-message-modal-form',
]); ?>

<button type="button" class="btn btn-success" data-bs-dismiss="modal"><?php echo Yii::t('app', 'BUTTON_OK')?></button>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>



    <!-- Модальное окно для вывода сообщений об ошибках при копировании события -->
<?php Modal::begin([
    'id' => 'viewMessageErrorCopyEventModalForm',
    'title' => '<h3>' . Yii::t('app', 'ERROR_COPY_EVENT') . '</h3>',
]); ?>

    <div class="modal-body">
        <p id="message-copy-event-text" style="font-size: 14px">
        </p>
    </div>

<?php $form = ActiveForm::begin([
    'id' => 'view-message-modal-form',
]); ?>

<button type="button" class="btn btn-success" data-bs-dismiss="modal"><?php echo Yii::t('app', 'BUTTON_OK')?></button>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>