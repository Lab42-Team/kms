<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Modal;
use yii\bootstrap5\Button;
use app\modules\main\models\Lang;
use app\modules\stde\models\StateProperty;

?>


<!-- Модальное окно добавления нового свойства состояния -->
<?php Modal::begin([
    'id' => 'addStatePropertyModalForm',
    'title' => '<h3>' . Yii::t('app', 'STATE_PROPERTY_ADD_NEW_STATE_PROPERTY') . '</h3>',
]); ?>

<!-- Скрипт модального окна -->
<script type="text/javascript">
    // Выполнение скрипта при загрузке страницы
    $(document).ready(function() {
        // Обработка нажатия кнопки сохранения
        $("#add-state-property-button").click(function(e) {
            e.preventDefault();
            var form = $("#add-state-property-form");
            // Ajax-запрос
            $.ajax({
                //переход на экшен левел
                url: "<?= Yii::$app->request->baseUrl . '/' . Lang::getCurrent()->url .
                '/state-transition-diagrams/add-state-property'?>",
                type: "post",
                data: form.serialize() + "&state_id_on_click=" + state_id_on_click,
                dataType: "json",
                success: function(data) {
                    // Если валидация прошла успешно (нет ошибок ввода)
                    if (data['success']) {
                        // Скрывание модального окна
                        $("#addStatePropertyModalForm").modal("hide");

                        //создание div свойства состояния
                        var div_state = document.getElementById('state_' + state_id_on_click);

                        //если свойство состояния первое
                        if (data['state_property_count'] == 0){
                            //добавляем div разделительной линии
                            var div_line = document.createElement('div');
                            div_line.id = 'state_line_' + state_id_on_click;
                            div_line.className = 'div-line';
                            div_state.append(div_line);
                        }

                        var div_property = document.createElement('div');
                        div_property.id = 'state_property_' + data['id'];
                        div_property.className = 'div-state-property';
                        div_property.innerHTML = data['name'] + " " + data['operator_name'] + " " + data['value'];
                        div_state.append(div_property);

                        var div_button_property = document.createElement('div');
                        div_button_property.className = 'button-state-property';
                        div_property.prepend(div_button_property);

                        var div_edit_property = document.createElement('div');
                        div_edit_property.id = 'state_property_edit_' + data['id'];
                        div_edit_property.className = 'edit-state-property glyphicon-pencil';
                        div_edit_property.title = '<?php echo Yii::t('app', 'BUTTON_EDIT'); ?>' ;
                        div_edit_property.innerHTML = '<i class="fa-solid fa-pen"></i>';
                        div_button_property.append(div_edit_property);

                        var div_del_property = document.createElement('div');
                        div_del_property.id = 'state_property_del_' + data['id'];
                        div_del_property.className = 'del-state-property glyphicon-trash';
                        div_del_property.title = '<?php echo Yii::t('app', 'BUTTON_DELETE'); ?>' ;
                        div_del_property.innerHTML = '<i class="fa-solid fa-trash"></i>';
                        div_button_property.append(div_del_property);


                        //добавлены новые записи в массив свойств состояний для изменений
                        var id = data['id'];
                        var name = data['name'];
                        var description = data['description'];
                        var operator = parseInt(data['operator'], 10);
                        var value = data['value'];
                        var state = data['state'];

                        var j = 0;
                        $.each(mas_data_state_property, function (i, elem) {
                            j = j + 1;
                        });
                        mas_data_state_property[j] = {id:id, name:name, description:description, operator:operator, value:value, state:state};

                        //обновление поля visual_diagram_field для размещения элементов
                        mousemoveState();
                        // Обновление формы редактора
                        instance.repaintEverything();

                        document.getElementById('add-state-property-form').reset();
                    } else {
                        // Отображение ошибок ввода
                        viewErrors("#add-state-property-form", data);
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
    'id' => 'add-state-property-form',
    'enableClientValidation' => true,
    'errorSummaryCssClass' => 'error-summary',
]); ?>


<?= $form->errorSummary($state_property_model); ?>

<?= $form->field($state_property_model, 'name')->textarea(['maxlength' => true, 'rows'=>1]) ?>

<?= $form->field($state_property_model, 'operator')->
    dropDownList(StateProperty::getOperatorArray(),['style'=>'width:100px;margin-left:40%'])->
        label(Yii::t('app', 'STATE_PROPERTY_MODEL_OPERATOR'),['style'=>'margin-left:40%'])?>

<?= $form->field($state_property_model, 'value')->textarea(['maxlength' => true, 'rows'=>1]) ?>

<?= $form->field($state_property_model, 'description')->textarea(['maxlength' => true, 'rows'=>3]) ?>


<?= Button::widget([
    'label' => Yii::t('app', 'BUTTON_ADD'),
    'options' => [
        'id' => 'add-state-property-button',
        'class' => 'btn-success',
        'style' => 'margin:5px'
    ]
]); ?>

<!-- Теперь не работает
<= Button::widget([
    'label' => Yii::t('app', 'BUTTON_CANCEL'),
    'options' => [
        'class' => 'btn-danger',
        'style' => 'margin:5px',
        'data-dismiss'=>'modal'
    ]
]); ?>-->

<button type="button" class="btn btn-danger" data-bs-dismiss="modal"><?php echo Yii::t('app', 'BUTTON_CANCEL')?></button>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>



<!-- Модальное окно изменения свойства состояния -->
<?php Modal::begin([
    'id' => 'editStatePropertyModalForm',
    'title' => '<h3>' . Yii::t('app', 'STATE_PROPERTY_EDIT_STATE_PROPERTY') . '</h3>',
]); ?>

<!-- Скрипт модального окна -->
<script type="text/javascript">
    // Выполнение скрипта при загрузке страницы
    $(document).ready(function() {
        // Обработка нажатия кнопки сохранения
        $("#edit-state-property-button").click(function(e) {
            e.preventDefault();
            var form = $("#edit-state-property-form");
            // Ajax-запрос
            $.ajax({
                //переход на экшен левел
                url: "<?= Yii::$app->request->baseUrl . '/' . Lang::getCurrent()->url .
                '/state-transition-diagrams/edit-state-property'?>",
                type: "post",
                data: form.serialize() + "&state_property_id_on_click=" + state_property_id_on_click,
                dataType: "json",
                success: function(data) {
                    // Если валидация прошла успешно (нет ошибок ввода)
                    if (data['success']) {
                        // Скрывание модального окна
                        $("#editStatePropertyModalForm").modal("hide");

                        //изменение div свойства состояния
                        var div_state_property = document.getElementById('state_property_' + state_property_id_on_click);
                        div_state_property.innerHTML = data['name'] + " " + data['operator_name'] + " " + data['value'];

                        var div_button_state_property = document.createElement('div');
                        div_button_state_property.className = 'button-state-property';
                        div_state_property.prepend(div_button_state_property);

                        var div_edit_state_property = document.createElement('div');
                        div_edit_state_property.id = 'state_property_edit_' + data['id'];
                        div_edit_state_property.className = 'edit-state-property glyphicon-pencil';
                        div_edit_state_property.title = '<?php echo Yii::t('app', 'BUTTON_EDIT'); ?>' ;
                        div_edit_state_property.innerHTML = '<i class="fa-solid fa-pen"></i>';
                        div_button_state_property.append(div_edit_state_property);

                        var div_del_state_property = document.createElement('div');
                        div_del_state_property.id = 'state_property_del_' + data['id'];
                        div_del_state_property.className = 'del-state-property glyphicon-trash';
                        div_del_state_property.title = '<?php echo Yii::t('app', 'BUTTON_DELETE'); ?>' ;
                        div_del_state_property.innerHTML = '<i class="fa-solid fa-trash"></i>';
                        div_button_state_property.append(div_del_state_property);

                        //изменена запись в массиве свойств состояний
                        $.each(mas_data_state_property, function (i, elem) {
                            if (elem.id == data['id']){
                                mas_data_state_property[i].name = data['name'];
                                mas_data_state_property[i].description = data['description'];
                                mas_data_state_property[i].operator = data['operator'];
                                mas_data_state_property[i].value = data['value'];
                            }
                        });

                        document.getElementById('edit-state-property-form').reset();
                    } else {
                        // Отображение ошибок ввода
                        viewErrors("#edit-state-property-form", data);
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
    'id' => 'edit-state-property-form',
    'enableClientValidation' => true,
    'errorSummaryCssClass' => 'error-summary',
]); ?>


<?= $form->errorSummary($state_property_model); ?>

<?= $form->field($state_property_model, 'name')->textarea(['maxlength' => true, 'rows'=>1]) ?>

<?= $form->field($state_property_model, 'operator')->
    dropDownList(StateProperty::getOperatorArray(),['style'=>'width:100px;margin-left:40%'])->
        label(Yii::t('app', 'STATE_PROPERTY_MODEL_OPERATOR'),['style'=>'margin-left:40%'])?>

<?= $form->field($state_property_model, 'value')->textarea(['maxlength' => true, 'rows'=>1]) ?>

<?= $form->field($state_property_model, 'description')->textarea(['maxlength' => true, 'rows'=>3]) ?>


<?= Button::widget([
    'label' => Yii::t('app', 'BUTTON_SAVE'),
    'options' => [
        'id' => 'edit-state-property-button',
        'class' => 'btn-success',
        'style' => 'margin:5px'
    ]
]); ?>

<button type="button" class="btn btn-danger" data-bs-dismiss="modal"><?php echo Yii::t('app', 'BUTTON_CANCEL')?></button>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>



<!-- Модальное окно удаления свойство состояния -->
<?php Modal::begin([
    'id' => 'deleteStatePropertyModalForm',
    'title' => '<h3>' . Yii::t('app', 'STATE_PROPERTY_DELETE_STATE_PROPERTY') . '</h3>',
]); ?>

<!-- Скрипт модального окна -->
<script type="text/javascript">
    // Выполнение скрипта при загрузке страницы
    $(document).ready(function() {
        // Обработка нажатия кнопки сохранения
        $("#delete-state-property-button").click(function(e) {
            e.preventDefault();
            // Ajax-запрос
            $.ajax({
                //переход на экшен левел
                url: "<?= Yii::$app->request->baseUrl . '/' . Lang::getCurrent()->url .
                '/state-transition-diagrams/delete-state-property'?>",
                type: "post",
                data: "YII_CSRF_TOKEN=<?= Yii::$app->request->csrfToken ?>" + "&state_property_id_on_click=" + state_property_id_on_click,
                dataType: "json",
                success: function(data) {
                    // Если валидация прошла успешно (нет ошибок ввода)
                    if (data['success']) {

                        //если свойств состояний больше нет
                        if (data['state_property_count'] == 0){
                            //удаление div линии
                            var div_line = document.getElementById('state_line_' + data['state_id']);
                            div_line.remove(); // удаляем
                        }

                        $("#deleteStatePropertyModalForm").modal("hide");

                        //удаление div свойства состояния
                        var div_state_property = document.getElementById('state_property_' + state_property_id_on_click);
                        div_state_property.remove(); // удаляем

                        //удалена запись в массиве свойств состояний
                        var temporary_mas_data_state_property = {};
                        var q = 0;
                        $.each(mas_data_state_property, function (i, elem) {
                            if (state_property_id_on_click != elem.id){
                                temporary_mas_data_state_property[q] = {
                                    "id":elem.id,
                                    "name":elem.name,
                                    "description":elem.description,
                                    "operator":elem.operator,
                                    "value":elem.value,
                                };
                                q = q+1;
                            }
                        });
                        mas_data_state_property = temporary_mas_data_state_property;

                        //обновление поля visual_diagram_field для размещения элементов
                        mousemoveState();
                        // Обновление формы редактора
                        instance.repaintEverything();
                    } else {
                        // Отображение ошибок ввода
                        viewErrors("#delete-state-property-form", data);
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
    'id' => 'delete-state-property-form',
]); ?>

<div class="modal-body">
    <p style="font-size: 14px">
        <?php echo Yii::t('app', 'DELETE_STATE_PROPERTY_TEXT'); ?>
    </p>
</div>

<?= Button::widget([
    'label' => Yii::t('app', 'BUTTON_DELETE'),
    'options' => [
        'id' => 'delete-state-property-button',
        'class' => 'btn-success',
        'style' => 'margin:5px'
    ]
]); ?>

<button type="button" class="btn btn-danger" data-bs-dismiss="modal"><?php echo Yii::t('app', 'BUTTON_CANCEL')?></button>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>
