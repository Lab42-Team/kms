<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\main\models\User;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Diagram */
/* @var $import_model app\modules\main\models\Diagram */

$this->title = Yii::t('app', 'DIAGRAMS_PAGE_UPLOAD_DECISION_TABLE');

$this->params['breadcrumbs'][] = Yii::$app->user->identity->role == User::ROLE_ADMINISTRATOR ?
    ['label' => Yii::t('app', 'DIAGRAMS_PAGE_DIAGRAMS'), 'url' => ['diagrams']] :
    ['label' => Yii::t('app', 'DIAGRAMS_PAGE_MY_DIAGRAMS'), 'url' => ['my-diagrams']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'DIAGRAMS_PAGE_DIAGRAM') . ' - ' . $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="upload-csv">

    <h1><?= Html::encode($this->title) ?></h1>

    <h3><?= Yii::t('app', 'DIAGRAMS_PAGE_UPLOAD_DECISION_TABLE_TEXT') ?></h3>

    <?php $form = ActiveForm::begin([
        'id' => 'import-main-form', 'options' => ['enctype' => 'multipart/form-data']
    ]); ?>

    <?= $form->field($import_model, 'file_name')->fileInput() ?>

    <div class="form-group">
        <?= Html::submitButton('<span class="glyphicon glyphicon-floppy-disk"></span> ' . Yii::t('app', 'BUTTON_UPLOAD'),
            ['class' => 'btn btn-success', 'name'=>'import-main-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

