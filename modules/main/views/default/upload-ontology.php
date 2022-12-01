<?php

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Diagram */
/* @var $owl_file_form app\modules\main\models\OWLFileForm */

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = Yii::t('app', 'DIAGRAMS_PAGE_UPLOAD_ONTOLOGY');

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'DIAGRAMS_PAGE_DIAGRAMS'),
    'url' => ['diagrams']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'DIAGRAMS_PAGE_DIAGRAM') . ': ' .
    $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<script type="text/javascript">
    // Выполнение скрипта при загрузке страницы
    $(document).ready(function() {
        // Обработка нажатия чекбокса импорта классов онтологии
        $("#owlfileform-class").change(function() {
            if(this.checked !== true)
                $("#class-fields").hide();
            else
                $("#class-fields").show();
        });
        // Обработка нажатия чекбокса импорта индивидов онтологии
        $("#owlfileform-individual").change(function() {
            if(this.checked !== true)
                $("#individual-fields").hide();
            else
                $("#individual-fields").show();
        });
    });
</script>

<div class="upload-ontology">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin([
        'id' => 'upload-ontology-form',
    ]); ?>

        <?= $form->field($owl_file_form, 'class')->checkbox(['value' => '1', 'checked ' => true]) ?>
        <div id="class-fields" class="well">
            <?= $form->field($owl_file_form, 'subclass_relation')->checkbox() ?>
            <?= $form->field($owl_file_form, 'class_object_property')->checkbox() ?>
            <?= $form->field($owl_file_form, 'class_datatype_property')->checkbox() ?>
        </div>

        <?= $form->field($owl_file_form, 'individual')->checkbox(['value' => '1', 'checked ' => true]) ?>
        <div id="individual-fields" class="well">
            <?= $form->field($owl_file_form, 'is_a_relation')->checkbox() ?>
            <?= $form->field($owl_file_form, 'individual_object_property')->checkbox() ?>
            <?= $form->field($owl_file_form, 'individual_datatype_property')->checkbox() ?>
        </div>

        <?= $form->field($owl_file_form, 'owl_file')->fileInput() ?>

        <div class="form-group">
            <?= Html::submitButton('<span class="glyphicon glyphicon-download-alt"></span> ' .
                Yii::t('app', 'BUTTON_UPLOAD'),
                ['class' => 'btn btn-success', 'name'=>'upload-ontology-button']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>