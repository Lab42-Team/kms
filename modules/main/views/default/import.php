<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app', 'DIAGRAMS_PAGE_IMPORT_DIAGRAM');

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'DIAGRAMS_PAGE_DIAGRAMS'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'DIAGRAMS_PAGE_DIAGRAM') . ' - ' . $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="main-import">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin([
        'id' => 'import-main-form', 'options' => ['enctype' => 'multipart/form-data']
    ]); ?>

    <?= $form->field($import_model, 'file_name')->fileInput() ?>

    <div class="form-group">
        <?= Html::submitButton('<span class="glyphicon glyphicon-floppy-disk"></span> ' . Yii::t('app', 'BUTTON_IMPORT'),
            ['class' => 'btn btn-success', 'name'=>'import-main-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>