<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use app\modules\main\models\User;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Diagram */
/* @var $import_model app\modules\main\models\Diagram */

$this->title = Yii::t('app', 'DIAGRAMS_PAGE_IMPORT_DIAGRAM');

$this->params['breadcrumbs'][] = Yii::$app->user->identity->role == User::ROLE_ADMINISTRATOR ?
    ['label' => Yii::t('app', 'DIAGRAMS_PAGE_DIAGRAMS'), 'url' => ['diagrams']] :
    ['label' => Yii::t('app', 'DIAGRAMS_PAGE_MY_DIAGRAMS'), 'url' => ['my-diagrams']];
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
        <?= Html::submitButton('<i class="fa-solid fa-floppy-disk"></i> ' . Yii::t('app', 'BUTTON_IMPORT'),
            ['class' => 'btn btn-success', 'name'=>'import-main-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>