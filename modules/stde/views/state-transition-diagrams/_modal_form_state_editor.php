<?php

use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
use yii\bootstrap\Button;
use app\modules\main\models\Lang;

/* @var $state_model app\modules\stde\models\Transition */

?>


<!-- Модальное окно добавления нового состояния -->
<?php Modal::begin([
    'id' => 'addStateModalForm',
    'header' => '<h3>' . Yii::t('app', 'STATE_ADD_NEW_STATE') . '</h3>',
]); ?>

<!-- Скрипт модального окна -->
<script type="text/javascript">
    // Выполнение скрипта при загрузке страницы
    $(document).ready(function() {
        // Обработка нажатия кнопки сохранения
        $("#add-state-button").click(function(e) {
            e.preventDefault();
            var form = $("#add-state-form");
            // Ajax-запрос
            $.ajax({
                //переход на экшен левел
                url: "<?= Yii::$app->request->baseUrl . '/' . Lang::getCurrent()->url .
                '/state-transition-diagrams/add-state/' . $model->id ?>",
                type: "post",
                data: form.serialize(),
                dataType: "json",
                success: function(data) {
                    // Если валидация прошла успешно (нет ошибок ввода)
                    if (data['success']) {
                        // Скрывание модального окна
                        $("#addStateModalForm").modal("hide");

                        //создание div состояния
                        var div_visual_diagram_field = document.getElementById('visual_diagram_field');

                        var div_state = document.createElement('div');
                        div_state.id = 'state_' + data['id'];
                        div_state.className = 'div-state';
                        div_state.title = data['description'];
                        div_visual_diagram_field.append(div_state);

                        var div_content_state = document.createElement('div');
                        div_content_state.className = 'content-state';
                        div_state.append(div_content_state);

                        var div_state_name = document.createElement('div');
                        div_state_name.id = 'state_name_' + data['id'];
                        div_state_name.className = 'div-state-name' ;
                        div_state_name.innerHTML = data['name'];
                        div_content_state.append(div_state_name);

                        var div_connect = document.createElement('div');
                        div_connect.className = 'connect-state glyphicon-share-alt' ;
                        div_connect.title = '<?php echo Yii::t('app', 'BUTTON_CONNECTION'); ?>' ;
                        div_content_state.append(div_connect);

                        var div_del = document.createElement('div');
                        div_del.id = 'state_del_' + data['id'];
                        div_del.className = 'del-state glyphicon-trash' ;
                        div_del.title = '<?php echo Yii::t('app', 'BUTTON_DELETE'); ?>' ;
                        div_content_state.append(div_del);

                        var div_edit = document.createElement('div');
                        div_edit.id = 'state_edit_' + data['id'];
                        div_edit.className = 'edit-state glyphicon-pencil' ;
                        div_edit.title = '<?php echo Yii::t('app', 'BUTTON_EDIT'); ?>' ;
                        div_content_state.append(div_edit);

                        var div_add_parameter = document.createElement('div');
                        div_add_parameter.id = 'state_add_property_' + data['id'];
                        div_add_parameter.className = 'add-state-property glyphicon-plus' ;
                        div_add_parameter.title = '<?php echo Yii::t('app', 'BUTTON_ADD'); ?>' ;
                        div_content_state.append(div_add_parameter);


                        //сделать div двигаемым
                        var div_state = document.getElementById('state_' + data['id']);
                        instance.draggable(div_state);
                        //добавляем элемент div_state в группу с именем group_field
                        instance.addToGroup('group_field', div_state);

                        instance.makeSource(div_state, {
                            filter: ".connect-state",
                            anchor: "Continuous", //непрерывный анкер
                        });

                        instance.makeTarget(div_state, {
                            dropOptions: { hoverClass: "dragHover" },
                            anchor: "Continuous", //непрерывный анкер
                            allowLoopback: true, // Разрешение создавать кольцевую связь
                        });


                        //добавлены новые записи в массив состояний для изменений
                        var j = 0;
                        $.each(mas_data_state, function (i, elem) {
                            j = j + 1;
                        });
                        mas_data_state[j] = {id:data['id'], indent_x:parseInt(data['indent_x'], 10), indent_y:parseInt(data['indent_y'], 10), name:data['name'], description:data['description']};


                        document.getElementById('add-state-form').reset();
                    } else {
                        // Отображение ошибок ввода
                        viewErrors("#add-state-form", data);
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
    'id' => 'add-state-form',
    'enableClientValidation' => true,
]); ?>


<?= $form->errorSummary($state_model); ?>

<?= $form->field($state_model, 'name')->textInput(['maxlength' => true]) ?>

<?= $form->field($state_model, 'description')->textarea(['maxlength' => true, 'rows'=>3]) ?>


<?= Button::widget([
    'label' => Yii::t('app', 'BUTTON_ADD'),
    'options' => [
        'id' => 'add-state-button',
        'class' => 'btn-success',
        'style' => 'margin:5px'
    ]
]); ?>

<?= Button::widget([
    'label' => Yii::t('app', 'BUTTON_CANCEL'),
    'options' => [
        'class' => 'btn-danger',
        'style' => 'margin:5px',
        'data-dismiss'=>'modal'
    ]
]); ?>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>



<!-- Модальное окно изменения состояния -->
<?php Modal::begin([
    'id' => 'editStateModalForm',
    'header' => '<h3>' . Yii::t('app', 'STATE_EDIT_STATE') . '</h3>',
]); ?>

    <!-- Скрипт модального окна -->
    <script type="text/javascript">
        // Выполнение скрипта при загрузке страницы
        $(document).ready(function() {
            // Обработка нажатия кнопки сохранения
            $("#edit-state-button").click(function(e) {
                e.preventDefault();
                var form = $("#edit-state-form");
                // Ajax-запрос
                $.ajax({
                    //переход на экшен левел
                    url: "<?= Yii::$app->request->baseUrl . '/' . Lang::getCurrent()->url .
                    '/state-transition-diagrams/edit-state'?>",
                    type: "post",
                    data: form.serialize() + "&state_id_on_click=" + state_id_on_click,
                    dataType: "json",
                    success: function(data) {
                        // Если валидация прошла успешно (нет ошибок ввода)
                        if (data['success']) {
                            // Скрывание модального окна
                            $("#editStateModalForm").modal("hide");

                            //изменение div состояния
                            var div_state_name = document.getElementById('state_name_' + data['id']);
                            div_state_name.innerHTML = data['name'];

                            var div_state = document.getElementById('state_' + data['id']);
                            div_state.title = data['description'];


                            //изменена запись в массиве состояний
                            $.each(mas_data_state, function (i, elem) {
                                if (elem.id == data['id']){
                                    mas_data_state[i].name = data['name'];
                                    mas_data_state[i].description = data['description'];
                                }
                            });

                            document.getElementById('edit-state-form').reset();
                        } else {
                            // Отображение ошибок ввода
                            viewErrors("#edit-state-form", data);
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
    'id' => 'edit-state-form',
    'enableClientValidation' => true,
]); ?>

<?= $form->errorSummary($state_model); ?>

<?= $form->field($state_model, 'name')->textInput(['maxlength' => true]) ?>

<?= $form->field($state_model, 'description')->textarea(['maxlength' => true, 'rows'=>3]) ?>

<?= Button::widget([
    'label' => Yii::t('app', 'BUTTON_SAVE'),
    'options' => [
        'id' => 'edit-state-button',
        'class' => 'btn-success',
        'style' => 'margin:5px'
    ]
]); ?>

<?= Button::widget([
    'label' => Yii::t('app', 'BUTTON_CANCEL'),
    'options' => [
        'class' => 'btn-danger',
        'style' => 'margin:5px',
        'data-dismiss'=>'modal'
    ]
]); ?>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>



<!-- Модальное окно удаления состояния -->
<?php Modal::begin([
    'id' => 'deleteStateModalForm',
    'header' => '<h3>' . Yii::t('app', 'STATE_DELETE_STATE') . '</h3>',
]); ?>

    <!-- Скрипт модального окна -->
    <script type="text/javascript">
        // Выполнение скрипта при загрузке страницы
        $(document).ready(function() {
            // Обработка нажатия кнопки сохранения
            $("#delete-state-button").click(function(e) {
                e.preventDefault();
                // Ajax-запрос
                $.ajax({
                    //переход на экшен левел
                    url: "<?= Yii::$app->request->baseUrl . '/' . Lang::getCurrent()->url .
                    '/state-transition-diagrams/delete-state'?>",
                    type: "post",
                    data: "YII_CSRF_TOKEN=<?= Yii::$app->request->csrfToken ?>" + "&state_id_on_click=" + state_id_on_click,
                    dataType: "json",
                    success: function(data) {
                        // Если валидация прошла успешно (нет ошибок ввода)
                        if (data['success']) {
                            // Скрывание модального окна
                            $("#deleteStateModalForm").modal("hide");

                            //удаление div состояния
                            var div_state = document.getElementById('state_' + state_id_on_click);
                            instance.removeFromGroup(div_state);//удаляем из группы
                            instance.remove(div_state);// удаляем состояние


                            //удалена запись в массиве состояний
                            var temporary_mas_data_state = {};
                            var q = 0;
                            $.each(mas_data_state, function (i, elem) {
                                if (state_id_on_click != elem.id){
                                    temporary_mas_data_state[q] = {
                                        "id":elem.id,
                                        "indent_x":elem.indent_x,
                                        "indent_y":elem.indent_y,
                                        "name":elem.name,
                                        "description":elem.description,

                                    };
                                    q = q+1;
                                }
                            });
                            mas_data_state = temporary_mas_data_state;


                            // ------------------- возможно нужно удалять запись из массива связей mas_data_transition

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
    'id' => 'delete-state-form',
]); ?>

    <div class="modal-body">
        <p style="font-size: 14px">
            <?php echo Yii::t('app', 'DELETE_STATE_TEXT'); ?>
        </p>
    </div>

<?= Button::widget([
    'label' => Yii::t('app', 'BUTTON_DELETE'),
    'options' => [
        'id' => 'delete-state-button',
        'class' => 'btn-success',
        'style' => 'margin:5px'
    ]
]); ?>

<?= Button::widget([
    'label' => Yii::t('app', 'BUTTON_CANCEL'),
    'options' => [
        'class' => 'btn-danger',
        'style' => 'margin:5px',
        'data-dismiss'=>'modal'
    ]
]); ?>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>