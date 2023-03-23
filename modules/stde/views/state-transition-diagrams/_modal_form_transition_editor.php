<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Modal;
use yii\bootstrap5\Button;
use app\modules\main\models\Lang;
use app\modules\stde\models\TransitionProperty;

/* @var $transition_model app\modules\stde\models\Transition */

?>

<!-- Модальное окно добавления нового перехода -->
<?php Modal::begin([
    'id' => 'addTransitionModalForm',
    'title' => '<h3>' . Yii::t('app', 'TRANSITION_ADD_NEW_TRANSITION') . '</h3>',
]); ?>

<!-- Скрипт модального окна -->
<script type="text/javascript">
    // Выполнение скрипта при загрузке страницы
    $(document).ready(function() {
        // Обработка нажатия кнопки сохранения
        $("#add-transition-button").click(function(e) {
            e.preventDefault();
            var form = $("#add-transition-form");
            // Ajax-запрос
            $.ajax({
                //переход на экшен левел
                url: "<?= Yii::$app->request->baseUrl . '/' . Lang::getCurrent()->url .
                '/state-transition-diagrams/add-transition'?>",
                type: "post",
                data: form.serialize() + "&id_state_from=" + id_state_from + "&id_state_to=" + id_state_to,
                dataType: "json",
                success: function(data) {
                    // Если валидация прошла успешно (нет ошибок ввода)
                    if (data['success']) {
                        //добавлен переход
                        added_transition = true;

                        // Скрывание модального окна
                        $("#addTransitionModalForm").modal("hide");

                        //присваиваем наименование и свойства новой связи
                        current_connection.setLabel({
                            label: data['name'],
                            location: 0.5, //расположение посередине
                            cssClass: "transitions-style",
                            id:"label_id_"+ data['id']
                        });

                        //создаем параметр для новой связи id_transition куда прописываем название связи transition_" +  data['id'] (как замена id)
                        current_connection.setParameter('id_transition',"transition_connect_" +  data['id']);

                        //создание div переходов и условий
                        var div_visual_diagram_field = document.getElementById('visual_diagram_field');

                        var div_transition = document.createElement('div');
                        div_transition.id = 'transition_' + data['id'];
                        div_transition.className = 'div-transition';
                        div_transition.style = 'visibility:hidden;'
                        div_visual_diagram_field.append(div_transition);

                        var div_content_transition = document.createElement('div');
                        div_content_transition.className = 'content-transition';
                        div_transition.append(div_content_transition);

                        var div_transition_name = document.createElement('div');
                        div_transition_name.id = 'transition_name_' + data['id'];
                        div_transition_name.className = 'div-transition-name' ;
                        div_transition_name.innerHTML = data['name'];
                        div_content_transition.append(div_transition_name);

                        var div_del = document.createElement('div');
                        div_del.id = 'transition_del_' + data['id'];
                        div_del.className = 'del-transition glyphicon-trash' ;
                        div_del.title = '<?php echo Yii::t('app', 'BUTTON_DELETE'); ?>' ;
                        div_del.innerHTML = '<i class="fa-solid fa-trash"></i>';
                        div_content_transition.append(div_del);

                        var div_edit = document.createElement('div');
                        div_edit.id = 'transition_edit_' + data['id'];
                        div_edit.className = 'edit-transition glyphicon-pencil' ;
                        div_edit.title = '<?php echo Yii::t('app', 'BUTTON_EDIT'); ?>' ;
                        div_edit.innerHTML = '<i class="fa-solid fa-pen"></i>';
                        div_content_transition.append(div_edit);

                        var div_hide = document.createElement('div');
                        div_hide.id = 'transition_hide_' + data['id'];
                        div_hide.className = 'hide-transition glyphicon-eye-close' ;
                        div_hide.title = '<?php echo Yii::t('app', 'BUTTON_HIDE'); ?>' ;
                        div_hide.innerHTML = '<i class="fa-solid fa-eye-slash"></i>';
                        div_content_transition.append(div_hide);

                        var div_add_property = document.createElement('div');
                        div_add_property.id = 'transition_add_property_' + data['id'];
                        div_add_property.className = 'add-transition-property glyphicon-plus' ;
                        div_add_property.title = '<?php echo Yii::t('app', 'BUTTON_ADD'); ?>';
                        div_add_property.innerHTML = '<i class="fa-solid fa-plus"></i>';
                        div_content_transition.append(div_add_property);

                        //добавляем div разделительной линии
                        var div_line = document.createElement('div');
                        div_line.id = 'transition_line_' + data['id'];
                        div_line.className = 'div-line';
                        div_transition.append(div_line);

                        var div_transition_property_name = document.createElement('div');
                        div_transition_property_name.id = 'transition_property_' + data['id_property'];
                        div_transition_property_name.className = 'div-transition-property' ;
                        div_transition_property_name.innerHTML = data['name_property'] + " " + data['operator_property'] + " " + data['value_property'];
                        div_transition.append(div_transition_property_name);

                        var div_button_transition_property = document.createElement('div');
                        div_button_transition_property.className = 'button-transition-property';
                        div_transition_property_name.prepend(div_button_transition_property);

                        var div_edit_transition_property = document.createElement('div');
                        div_edit_transition_property.id = 'transition_property_edit_' + data['id_property'];
                        div_edit_transition_property.className = 'edit-transition-property glyphicon-pencil';
                        div_edit_transition_property.title = '<?php echo Yii::t('app', 'BUTTON_EDIT'); ?>' ;
                        div_edit_transition_property.innerHTML = '<i class="fa-solid fa-pen"></i>';
                        div_button_transition_property.append(div_edit_transition_property);

                        var div_del_transition_property = document.createElement('div');
                        div_del_transition_property.id = 'transition_property_del_' + data['id_property'];
                        div_del_transition_property.className = 'del-transition-property glyphicon-trash';
                        div_del_transition_property.title = '<?php echo Yii::t('app', 'BUTTON_DELETE'); ?>' ;
                        div_del_transition_property.innerHTML = '<i class="fa-solid fa-trash"></i>';
                        div_button_transition_property.append(div_del_transition_property);


                        //сделать div двигаемым
                        //var div_transition = document.getElementById('transition_' + data['id']);
                        //instance.draggable(div_transition);
                        //добавляем элемент div_transition в группу с именем group_field
                        //instance.addToGroup('group_field', div_transition);


                        //добавлены новые записи в массивы связи для изменений
                        var j = 0;
                        $.each(mas_data_transition, function (i, elem) {
                            j = j + 1;
                        });
                        mas_data_transition[j] = {id:data['id'], name:data['name'], description:data['description'],
                            state_from:parseInt(data['state_from'], 10), state_to:parseInt(data['state_to'], 10)};


                        //добавление новой записи в массив свойств состояний для изменений
                        var id = data['id_property'];
                        var name = data['name_property'];
                        var description = data['description_property'];
                        var operator = parseInt(data['operator_property'], 10);
                        var value = data['value_property'];
                        var transition = data['id'];

                        var j = 0;
                        $.each(mas_data_transition_property, function (i, elem) {
                            j = j + 1;
                        });
                        mas_data_transition_property[j] = {id:id, name:name, description:description, operator:operator, value:value, transition:transition};


                        document.getElementById('add-transition-form').reset();
                    } else {
                        // Отображение ошибок ввода
                        viewErrors("#add-transition-form", data);
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
    'id' => 'add-transition-form',
    'enableClientValidation' => true,
    'errorSummaryCssClass' => 'error-summary',
]); ?>


<?= $form->errorSummary($transition_model); ?>

<?= $form->field($transition_model, 'name')->textarea(['maxlength' => true, 'rows'=>1]) ?>

<?= $form->field($transition_model, 'description')->textarea(['maxlength' => true, 'rows'=>3]) ?>


<label class="control-label transition-properties-block"><?php echo Yii::t('app', 'TRANSITION_PROPERTY_ADD_NEW_TRANSITION_PROPERTY'); ?></label>

<div class="line">

<?= $form->field($transition_model, 'name_property')->textarea(['maxlength' => true, 'rows'=>1]) ?>

<?= $form->field($transition_model, 'operator_property')->
    dropDownList(TransitionProperty::getOperatorArray(),['style'=>'width:100px;margin-left:40%'])->
        label(Yii::t('app', 'TRANSITION_MODEL_OPERATOR_PROPERTY'),['style'=>'margin-left:40%'])?>

<?= $form->field($transition_model, 'value_property')->textarea(['maxlength' => true, 'rows'=>1]) ?>

<?= $form->field($transition_model, 'description_property')->textarea(['maxlength' => true, 'rows'=>3]) ?>

</div>

<?= Button::widget([
    'label' => Yii::t('app', 'BUTTON_SAVE'),
    'options' => [
        'id' => 'add-transition-button',
        'class' => 'btn-success',
        'style' => 'margin:5px'
    ]
]); ?>

<button type="button" class="btn btn-danger" data-bs-dismiss="modal"><?php echo Yii::t('app', 'BUTTON_CANCEL')?></button>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>



<!-- Модальное окно изменения перехода -->
<?php Modal::begin([
    'id' => 'editTransitionModalForm',
    'title' => '<h3>' . Yii::t('app', 'TRANSITION_EDIT_TRANSITION') . '</h3>',
]); ?>

<!-- Скрипт модального окна -->
<script type="text/javascript">
    // Выполнение скрипта при загрузке страницы
    $(document).ready(function() {
        // Обработка нажатия кнопки сохранения
        $("#edit-transition-button").click(function(e) {
            e.preventDefault();
            var form = $("#edit-transition-form");
            // Ajax-запрос
            $.ajax({
                //переход на экшен левел
                url: "<?= Yii::$app->request->baseUrl . '/' . Lang::getCurrent()->url .
                '/state-transition-diagrams/edit-transition'?>",
                type: "post",
                data: form.serialize() + "&transition_id_on_click=" + transition_id_on_click,
                dataType: "json",
                success: function(data) {
                    // Если валидация прошла успешно (нет ошибок ввода)
                    if (data['success']) {
                        // Скрывание модального окна
                        $("#editTransitionModalForm").modal("hide");

                        //изменение div перехода
                        var div_transition_name_ = document.getElementById('transition_name_' + data['id']);
                        div_transition_name_.innerHTML = data['name'];

                        //изменена запись в массиве состояний
                        $.each(mas_data_transition, function (i, elem) {
                            if (elem.id == data['id']){
                                mas_data_transition[i].name = data['name'];
                                mas_data_transition[i].description = data['description'];
                            }
                        });

                        var id_source = "state_" + data['state_from'];
                        var id_target = "state_" + data['state_to'];

                        //скрытие старого label на связи
                        var overlay = instance.select({source:id_source, target:id_target});
                        overlay.hideOverlay("label_id_" + data['id']);

                        //создание нового label с новым наименованием на связи
                        instance.select({source:id_source, target:id_target}).setLabel({
                            label: data['name'],
                            location: 0.5, //расположение посередине
                            cssClass: "transitions-style",
                            id:"label_id_"+ data['id']
                        });

                        document.getElementById('edit-transition-form').reset();
                    } else {
                        // Отображение ошибок ввода
                        viewErrors("#edit-transition-form", data);
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
    'id' => 'edit-transition-form',
    'enableClientValidation' => true,
    'errorSummaryCssClass' => 'error-summary',
]); ?>

<?= $form->errorSummary($transition_model); ?>

<?= $form->field($transition_model, 'name')->textarea(['maxlength' => true, 'rows'=>1]) ?>

<?= $form->field($transition_model, 'description')->textarea(['maxlength' => true, 'rows'=>3]) ?>

<!-- Скрытые обязательные поля -->

<?= $form->field($transition_model, 'name_property')->hiddenInput()->label(false) ?>

<?= $form->field($transition_model, 'operator_property')->hiddenInput()->label(false) ?>

<?= $form->field($transition_model, 'value_property')->hiddenInput()->label(false) ?>


<?= Button::widget([
    'label' => Yii::t('app', 'BUTTON_SAVE'),
    'options' => [
        'id' => 'edit-transition-button',
        'class' => 'btn-success',
        'style' => 'margin:5px'
    ]
]); ?>

<button type="button" class="btn btn-danger" data-bs-dismiss="modal"><?php echo Yii::t('app', 'BUTTON_CANCEL')?></button>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>



<!-- Модальное окно удаления перехода -->
<?php Modal::begin([
    'id' => 'deleteTransitionModalForm',
    'title' => '<h3>' . Yii::t('app', 'TRANSITION_DELETE_TRANSITION') . '</h3>',
]); ?>

    <!-- Скрипт модального окна -->
    <script type="text/javascript">
        // Выполнение скрипта при загрузке страницы
        $(document).ready(function() {
            // Обработка нажатия кнопки сохранения
            $("#delete-transition-button").click(function(e) {
                e.preventDefault();
                // Ajax-запрос
                $.ajax({
                    //переход на экшен левел
                    url: "<?= Yii::$app->request->baseUrl . '/' . Lang::getCurrent()->url .
                    '/state-transition-diagrams/delete-transition'?>",
                    type: "post",
                    data: "YII_CSRF_TOKEN=<?= Yii::$app->request->csrfToken ?>" + "&transition_id_on_click=" + transition_id_on_click,
                    dataType: "json",
                    success: function(data) {
                        // Если валидация прошла успешно (нет ошибок ввода)
                        if (data['success']) {
                            // Скрывание модального окна
                            $("#deleteTransitionModalForm").modal("hide");

                            //удаление div перехода
                            var div_transition = document.getElementById('transition_' + transition_id_on_click);
                            instance.removeFromGroup(div_transition);//удаляем из группы
                            instance.remove(div_transition);// удаляем состояние

                            //поиск связи
                            var id_source = "state_" + data['state_from'];
                            var id_target = "state_" + data['state_to'];

                            var connection = instance.getConnections({
                                source: id_source,
                                target: id_target
                            })[ 0 ];

                            //удаление связи
                            removed_transition = true;
                            instance.deleteConnection(connection);

                            //удалена запись в массиве состояний
                            var temporary_mas_data_transition = {};
                            var q = 0;
                            $.each(mas_data_transition, function (i, elem) {
                                if (transition_id_on_click != elem.id){
                                    temporary_mas_data_transition[q] = {
                                        "id":elem.id,
                                        "name":elem.name,
                                        "description":elem.description,
                                        "state_from":elem.state_from,
                                        "state_to":elem.state_to,
                                    };
                                    q = q+1;
                                }
                            });
                            mas_data_transition = temporary_mas_data_transition;
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
    'id' => 'delete-transition-form',
]); ?>

    <div class="modal-body">
        <p style="font-size: 14px">
            <?php echo Yii::t('app', 'DELETE_TRANSITION_TEXT'); ?>
        </p>
    </div>

<?= Button::widget([
    'label' => Yii::t('app', 'BUTTON_DELETE'),
    'options' => [
        'id' => 'delete-transition-button',
        'class' => 'btn-success',
        'style' => 'margin:5px'
    ]
]); ?>

<button type="button" class="btn btn-danger" data-bs-dismiss="modal"><?php echo Yii::t('app', 'BUTTON_CANCEL')?></button>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>