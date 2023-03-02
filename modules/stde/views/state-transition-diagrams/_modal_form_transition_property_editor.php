<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Modal;
use yii\bootstrap5\Button;
use app\modules\main\models\Lang;
use app\modules\stde\models\TransitionProperty;

?>


<!-- Модальное окно добавления нового условия -->
<?php Modal::begin([
    'id' => 'addTransitionPropertyModalForm',
    'title' => '<h3>' . Yii::t('app', 'TRANSITION_PROPERTY_ADD_NEW_TRANSITION_PROPERTY') . '</h3>',
]); ?>

<!-- Скрипт модального окна -->
<script type="text/javascript">
    // Выполнение скрипта при загрузке страницы
    $(document).ready(function() {
        // Обработка нажатия кнопки сохранения
        $("#add-transition-property-button").click(function(e) {
            e.preventDefault();
            var form = $("#add-transition-property-form");
            // Ajax-запрос
            $.ajax({
                //переход на экшен левел
                url: "<?= Yii::$app->request->baseUrl . '/' . Lang::getCurrent()->url .
                '/state-transition-diagrams/add-transition-property'?>",
                type: "post",
                data: form.serialize() + "&transition_id_on_click=" + transition_id_on_click,
                dataType: "json",
                success: function(data) {
                    // Если валидация прошла успешно (нет ошибок ввода)
                    if (data['success']) {
                        // Скрывание модального окна
                        $("#addTransitionPropertyModalForm").modal("hide");

                        //создание div условия
                        var div_transition = document.getElementById('transition_' + transition_id_on_click);

                        //если свойство состояния первое
                        if (data['transition_property_count'] == 0){
                            //добавляем div разделительной линии
                            var div_line = document.createElement('div');
                            div_line.id = 'transition_line_' + transition_id_on_click;
                            div_line.className = 'div-line';
                            div_transition.append(div_line);
                        }

                        var div_property = document.createElement('div');
                        div_property.id = 'transition_property_' + data['id'];
                        div_property.className = 'div-transition-property';
                        div_property.innerHTML = data['name'] + " " + data['operator_name'] + " " + data['value'];
                        div_transition.append(div_property);

                        var div_button_property = document.createElement('div');
                        div_button_property.className = 'button-transition-property';
                        div_property.prepend(div_button_property);

                        var div_edit_property = document.createElement('div');
                        div_edit_property.id = 'transition_property_edit_' + data['id'];
                        div_edit_property.className = 'edit-transition-property glyphicon-pencil';
                        div_edit_property.title = '<?php echo Yii::t('app', 'BUTTON_EDIT'); ?>' ;
                        div_edit_property.innerHTML = '<i class="fa-solid fa-pen"></i>';
                        div_button_property.append(div_edit_property);

                        var div_del_property = document.createElement('div');
                        div_del_property.id = 'transition_property_del_' + data['id'];
                        div_del_property.className = 'del-transition-property glyphicon-trash';
                        div_del_property.title = '<?php echo Yii::t('app', 'BUTTON_DELETE'); ?>' ;
                        div_del_property.innerHTML = '<i class="fa-solid fa-trash"></i>';
                        div_button_property.append(div_del_property);

                        //добавлены новые записи в массив условия для изменений
                        var id = data['id'];
                        var name = data['name'];
                        var description = data['description'];
                        var operator = parseInt(data['operator'], 10);
                        var value = data['value'];
                        var transition = data['transition'];

                        var j = 0;
                        $.each(mas_data_transition_property, function (i, elem) {
                            j = j + 1;
                        });
                        mas_data_transition_property[j] = {id:id, name:name, description:description, operator:operator, value:value, transition:transition};

                        // Обновление формы редактора
                        instance.repaintEverything();

                        document.getElementById('add-transition-property-form').reset();
                    } else {
                        // Отображение ошибок ввода
                        viewErrors("#add-transition-property-form", data);
                    }
                },
                error: function() {
                    alert('Error!');
                }
            });
        });
    });
</script>

<?php $form = ActiveForm::begin([
    'id' => 'add-transition-property-form',
    'enableClientValidation' => true,
    'errorSummaryCssClass' => 'error-summary',
]); ?>

<?= $form->errorSummary($transition_property_model); ?>

<?= $form->field($transition_property_model, 'name')->textarea(['maxlength' => true, 'rows'=>1]) ?>

<?= $form->field($transition_property_model, 'operator')->
    dropDownList(TransitionProperty::getOperatorArray(),['style'=>'width:100px;margin-left:40%'])->
        label(Yii::t('app', 'TRANSITION_PROPERTY_MODEL_OPERATOR'),['style'=>'margin-left:40%'])?>

<?= $form->field($transition_property_model, 'value')->textarea(['maxlength' => true, 'rows'=>1]) ?>

<?= $form->field($transition_property_model, 'description')->textarea(['maxlength' => true, 'rows'=>3]) ?>


<?= Button::widget([
    'label' => Yii::t('app', 'BUTTON_ADD'),
    'options' => [
        'id' => 'add-transition-property-button',
        'class' => 'btn-success',
        'style' => 'margin:5px'
    ]
]); ?>

<button type="button" class="btn btn-danger" data-bs-dismiss="modal"><?php echo Yii::t('app', 'BUTTON_CANCEL')?></button>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>



<!-- Модальное окно изменения условия -->
<?php Modal::begin([
    'id' => 'editTransitionPropertyModalForm',
    'title' => '<h3>' . Yii::t('app', 'TRANSITION_PROPERTY_EDIT_TRANSITION_PROPERTY') . '</h3>',
]); ?>

<!-- Скрипт модального окна -->
<script type="text/javascript">
    // Выполнение скрипта при загрузке страницы
    $(document).ready(function() {
        // Обработка нажатия кнопки сохранения
        $("#edit-transition-property-button").click(function(e) {
            e.preventDefault();
            var form = $("#edit-transition-property-form");
            // Ajax-запрос
            $.ajax({
                //переход на экшен левел
                url: "<?= Yii::$app->request->baseUrl . '/' . Lang::getCurrent()->url .
                '/state-transition-diagrams/edit-transition-property'?>",
                type: "post",
                data: form.serialize() + "&transition_property_id_on_click=" + transition_property_id_on_click,
                dataType: "json",
                success: function(data) {
                    // Если валидация прошла успешно (нет ошибок ввода)
                    if (data['success']) {
                        // Скрывание модального окна
                        $("#editTransitionPropertyModalForm").modal("hide");

                        //изменение div условия
                        var div_transition_property = document.getElementById('transition_property_' + transition_property_id_on_click);
                        div_transition_property.innerHTML = data['name'] + " " + data['operator_name'] + " " + data['value'];

                        var div_button_transition_property = document.createElement('div');
                        div_button_transition_property.className = 'button-transition-property';
                        div_transition_property.prepend(div_button_transition_property);

                        var div_edit_transition_property = document.createElement('div');
                        div_edit_transition_property.id = 'transition_property_edit_' + data['id'];
                        div_edit_transition_property.className = 'edit-transition-property glyphicon-pencil';
                        div_edit_transition_property.title = '<?php echo Yii::t('app', 'BUTTON_EDIT'); ?>' ;
                        div_edit_transition_property.innerHTML = '<i class="fa-solid fa-pen"></i>';
                        div_button_transition_property.append(div_edit_transition_property);

                        var div_del_transition_property = document.createElement('div');
                        div_del_transition_property.id = 'transition_property_del_' + data['id'];
                        div_del_transition_property.className = 'del-transition-property glyphicon-trash';
                        div_del_transition_property.title = '<?php echo Yii::t('app', 'BUTTON_DELETE'); ?>' ;
                        div_del_transition_property.innerHTML = '<i class="fa-solid fa-trash"></i>';
                        div_button_transition_property.append(div_del_transition_property);

                        //изменена запись в массиве условия
                        $.each(mas_data_transition_property, function (i, elem) {
                            if (elem.id == data['id']){
                                mas_data_transition_property[i].name = data['name'];
                                mas_data_transition_property[i].description = data['description'];
                                mas_data_transition_property[i].operator = data['operator'];
                                mas_data_transition_property[i].value = data['value'];
                            }
                        });

                        document.getElementById('edit-transition-property-form').reset();
                    } else {
                        // Отображение ошибок ввода
                        viewErrors("#edit-transition-property-form", data);
                    }
                },
                error: function() {
                    alert('Error!');
                }
            });
        });
    });
</script>

<?php $form = ActiveForm::begin([
    'id' => 'edit-transition-property-form',
    'enableClientValidation' => true,
    'errorSummaryCssClass' => 'error-summary',
]); ?>

<?= $form->errorSummary($transition_property_model); ?>

<?= $form->field($transition_property_model, 'name')->textarea(['maxlength' => true, 'rows'=>1]) ?>

<?= $form->field($transition_property_model, 'operator')->
    dropDownList(TransitionProperty::getOperatorArray(),['style'=>'width:100px;margin-left:40%'])->
        label(Yii::t('app', 'TRANSITION_PROPERTY_MODEL_OPERATOR'),['style'=>'margin-left:40%'])?>

<?= $form->field($transition_property_model, 'value')->textarea(['maxlength' => true, 'rows'=>1]) ?>

<?= $form->field($transition_property_model, 'description')->textarea(['maxlength' => true, 'rows'=>3]) ?>


<?= Button::widget([
    'label' => Yii::t('app', 'BUTTON_SAVE'),
    'options' => [
        'id' => 'edit-transition-property-button',
        'class' => 'btn-success',
        'style' => 'margin:5px'
    ]
]); ?>

<button type="button" class="btn btn-danger" data-bs-dismiss="modal"><?php echo Yii::t('app', 'BUTTON_CANCEL')?></button>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>



<!-- Модальное окно удаления условия -->
<?php Modal::begin([
    'id' => 'deleteTransitionPropertyModalForm',
    'title' => '<h3>' . Yii::t('app', 'TRANSITION_PROPERTY_DELETE_TRANSITION_PROPERTY') . '</h3>',
]); ?>

<!-- Скрипт модального окна -->
<script type="text/javascript">
    // Выполнение скрипта при загрузке страницы
    $(document).ready(function() {
        // Обработка нажатия кнопки сохранения
        $("#delete-transition-property-button").click(function(e) {
            e.preventDefault();
            // Ajax-запрос
            $.ajax({
                //переход на экшен левел
                url: "<?= Yii::$app->request->baseUrl . '/' . Lang::getCurrent()->url .
                '/state-transition-diagrams/delete-transition-property'?>",
                type: "post",
                data: "YII_CSRF_TOKEN=<?= Yii::$app->request->csrfToken ?>" + "&transition_property_id_on_click=" + transition_property_id_on_click,
                dataType: "json",
                success: function(data) {
                    // Если валидация прошла успешно (нет ошибок ввода)
                    if (data['success']) {
                        $("#deleteTransitionPropertyModalForm").modal("hide");

                        //если условия больше нет
                        if (data['transition_property_count'] == 0){
                            //удаление div линии
                            var div_line = document.getElementById('transition_line_' + data['transition_id']);
                            div_line.remove(); // удаляем
                        }

                        //удаление div условия
                        var div_transition_property = document.getElementById('transition_property_' + transition_property_id_on_click);
                        div_transition_property.remove(); // удаляем

                        //удалена запись в массиве условий
                        var temporary_mas_data_transition_property = {};
                        var q = 0;
                        $.each(mas_data_transition_property, function (i, elem) {
                            if (transition_property_id_on_click != elem.id){
                                temporary_mas_data_transition_property[q] = {
                                    "id":elem.id,
                                    "name":elem.name,
                                    "description":elem.description,
                                    "operator":elem.operator,
                                    "value":elem.value,
                                };
                                q = q+1;
                            }
                        });
                        mas_data_transition_property = temporary_mas_data_transition_property;

                        // Обновление формы редактора
                        instance.repaintEverything();
                    } else {
                        // Отображение ошибок ввода
                        viewErrors("#delete-transition-property-form", data);
                    }
                },
                error: function() {
                    alert('Error!');
                }
            });
        });
    });
</script>

<?php $form = ActiveForm::begin([
    'id' => 'delete-transition-property-form',
]); ?>

<div class="modal-body">
    <p style="font-size: 14px">
        <?php echo Yii::t('app', 'DELETE_TRANSITION_PROPERTY_TEXT'); ?>
    </p>
</div>

<?= Button::widget([
    'label' => Yii::t('app', 'BUTTON_DELETE'),
    'options' => [
        'id' => 'delete-transition-property-button',
        'class' => 'btn-success',
        'style' => 'margin:5px'
    ]
]); ?>

<button type="button" class="btn btn-danger" data-bs-dismiss="modal"><?php echo Yii::t('app', 'BUTTON_CANCEL')?></button>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>
