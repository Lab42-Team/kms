<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use app\modules\main\models\Diagram;
use app\modules\eete\models\TreeDiagram;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Diagram */
/* @var $form yii\bootstrap5\ActiveForm */
?>

<?php
    $this->registerCssFile('/css/create.css', ['position'=>yii\web\View::POS_HEAD]);
?>

<script>
    //скрытие блока event_tree
    $(document).ready(function() {
        var dt = document.getElementById("diagram-type");
        var value = dt.options[dt.selectedIndex].value;
        var block = document.getElementById("block_event_tree");

        if (value == <?= Diagram::EVENT_TREE_TYPE ?>){
            block.style.display = ""
        } else {
            block.style.display = "none"
        }
    });

    $(document).on('change', '#diagram-type', function() {
        var dt = document.getElementById("diagram-type");
        var value = dt.options[dt.selectedIndex].value;

        var block = document.getElementById("block_event_tree");

        if (value == <?= Diagram::EVENT_TREE_TYPE ?>){
            block.style.display = ""
        } else {
            block.style.display = "none"
        }
    });
</script>

<div class="diagram-form">

    <?php $form = ActiveForm::begin([
        'id' => 'create-diagram-form',
        'options' => ['enctype' => 'multipart/form-data']
    ]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 8]) ?>

    <?= $form->field($model, 'type')->dropDownList(Diagram::getTypesArray()) ?>

    <?= $form->field($model, 'status')->dropDownList(Diagram::getStatusesArray()) ?>

    <div id="block_event_tree" class="line" style="display: none">

        <?= $form->field($model, 'mode_tree_diagram')->dropDownList(TreeDiagram::getModesArray()) ?>

    </div>

    <div class="form-group">
        <?= Html::submitButton('<span class="glyphicon glyphicon-floppy-disk"></span> ' .
            Yii::t('app', 'BUTTON_SAVE'), ['class' => 'btn btn-success',
            'name'=>'create-diagram-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>