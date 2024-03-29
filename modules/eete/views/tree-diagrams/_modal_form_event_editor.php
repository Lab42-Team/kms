<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Modal;
use yii\bootstrap5\Button;
use app\modules\main\models\Lang;
use app\modules\eete\models\Node;
use app\modules\eete\models\TreeDiagram;

/* @var $node_model app\modules\eete\models\Node */
/* @var $array_levels app\modules\eete\controllers\TreeDiagramsController */

?>

<script type="text/javascript">
    $(document).on('change', '#node-level_id', function() {
        var alert = document.getElementById('alert_event_level_id');
        alert.style = "";
    });

    // Обработка открытия модального окна добавления нового события
    $("#addEventModalForm").on("show.bs.modal", function() {
        //если начальное событие есть тогда
        var initial_event = document.getElementsByClassName("div-initial-event");
        if ((initial_event.length == 0)||(<?= TreeDiagram::CLASSIC_TREE_MODE ?> == <?= $model_tree_diagram->mode ?>)){
            //блокировка изменения левела
            document.forms["add-event-form"].elements["Node[level_id]"].style.display = "none";
            document.getElementById('add_label_level').style.display = "none";
        } else {
            document.forms["add-event-form"].elements["Node[level_id]"].style.display = "";
            document.getElementById('add_label_level').style.display = "";
        }
    });
</script>

<!-- Модальное окно добавления нового события -->
<?php Modal::begin([
    'id' => 'addEventModalForm',
    'title' => '<h3>' . Yii::t('app', 'EVENT_ADD_NEW_EVENT') . '</h3>',
]); ?>

    <!-- Скрипт модального окна -->
    <script type="text/javascript">
        // Выполнение скрипта при загрузке страницы
        $(document).ready(function() {
            // Обработка нажатия кнопки сохранения
            $("#add-event-button").click(function(e) {
                e.preventDefault();
                var form = $("#add-event-form");
                // Ajax-запрос
                $.ajax({
                    //переход на экшен левел
                    url: "<?= Yii::$app->request->baseUrl . '/' . Lang::getCurrent()->url .
                        '/tree-diagrams/add-event/' . $model_tree_diagram->id ?>",
                    type: "post",
                    data: form.serialize(),
                    dataType: "json",
                    success: function(data) {
                        // Если валидация прошла успешно (нет ошибок ввода)
                        if (data['success']) {


                            if (data['type'] == <?= Node::INITIAL_EVENT_TYPE ?>){
                                var div_level_layer = document.getElementById('level_description_' + data['id_level']);

                                var div_initial_event = document.createElement('div');
                                div_initial_event.id = 'node_' + data['id'];
                                div_initial_event.className = 'div-event node div-initial-event';
                                div_level_layer.append(div_initial_event);

                                var div_content_event = document.createElement('div');
                                div_content_event.className = 'content-event';
                                div_initial_event.append(div_content_event);

                                var div_initial_event_name = document.createElement('div');
                                div_initial_event_name.id = 'node_name_' + data['id'];
                                div_initial_event_name.className = 'div-event-name' ;
                                if ((data['certainty_factor'] == "")||(data['certainty_factor'] == 0)){
                                    div_initial_event_name.innerHTML = data['name'];
                                } else {
                                    div_initial_event_name.innerHTML = data['name'] + ' (' + data['certainty_factor'] + ')';
                                }
                                div_content_event.append(div_initial_event_name);

                                var div_ep = document.createElement('div');
                                div_ep.className = 'ep ep-event' ;
                                div_ep.title = '<?php echo Yii::t('app', 'BUTTON_CONNECTION'); ?>' ;
                                div_ep.innerHTML = '<i class="fa-solid fa-share"></i>';
                                div_content_event.append(div_ep);

                                var div_del = document.createElement('div');
                                div_del.id = 'node_del_' + data['id'];
                                div_del.className = 'del del-event glyphicon-trash' ;
                                div_del.title = '<?php echo Yii::t('app', 'BUTTON_DELETE'); ?>' ;
                                div_del.innerHTML = '<i class="fa-solid fa-trash"></i>';
                                div_content_event.append(div_del);

                                var div_edit = document.createElement('div');
                                div_edit.id = 'node_edit_' + data['id'];
                                div_edit.className = 'edit edit-event glyphicon-pencil' ;
                                div_edit.title = '<?php echo Yii::t('app', 'BUTTON_EDIT'); ?>' ;
                                div_edit.innerHTML = '<i class="fa-solid fa-pen"></i>';
                                div_content_event.append(div_edit);

                                var div_add_parameter = document.createElement('div');
                                div_add_parameter.id = 'node_add_parameter_' + data['id'];
                                div_add_parameter.className = 'param add-parameter glyphicon-plus' ;
                                div_add_parameter.title = '<?php echo Yii::t('app', 'BUTTON_ADD'); ?>' ;
                                div_add_parameter.innerHTML = '<i class="fa-solid fa-plus"></i>';
                                div_content_event.append(div_add_parameter);

                                var div_show_comment = document.createElement('div');
                                div_show_comment.id = 'node_show_comment_' + data['id'];
                                div_show_comment.className = 'show-event-comment glyphicon-paperclip' ;
                                div_show_comment.title = '<?php echo Yii::t('app', 'BUTTON_COMMENT'); ?>' ;
                                div_show_comment.innerHTML = '<i class="fa-solid fa-paperclip"></i>';
                                div_content_event.append(div_show_comment);

                                var div_copy = document.createElement('div');
                                div_copy.id = 'node_copy_' + data['id'];
                                div_copy.className = 'copy-event glyphicon-plus-sign' ;
                                div_copy.title = '<?php echo Yii::t('app', 'BUTTON_COPY'); ?>' ;
                                div_copy.innerHTML = '<i class="fa-solid fa-circle-plus"></i>';
                                div_content_event.append(div_copy);
                            } else {
                                var div_level_layer = document.getElementById('level_description_' + data['id_level']);

                                var div_event = document.createElement('div');
                                div_event.id = 'node_' + data['id'];
                                div_event.className = 'div-event node';
                                div_level_layer.append(div_event);

                                var div_content_event = document.createElement('div');
                                div_content_event.className = 'content-event';
                                div_event.append(div_content_event);

                                var div_event_name = document.createElement('div');
                                div_event_name.id = 'node_name_' + data['id'];
                                div_event_name.className = 'div-event-name' ;
                                if ((data['certainty_factor'] == "")||(data['certainty_factor'] == 0)){
                                    div_event_name.innerHTML = data['name'];
                                } else {
                                    div_event_name.innerHTML = data['name'] + ' (' + data['certainty_factor'] + ')';
                                }
                                div_content_event.append(div_event_name);

                                var div_ep = document.createElement('div');
                                div_ep.className = 'ep ep-event' ;
                                div_ep.title = '<?php echo Yii::t('app', 'BUTTON_CONNECTION'); ?>' ;
                                div_ep.innerHTML = '<i class="fa-solid fa-share"></i>';
                                div_content_event.append(div_ep);

                                var div_del = document.createElement('div');
                                div_del.id = 'node_del_' + data['id'];
                                div_del.className = 'del del-event glyphicon-trash' ;
                                div_del.title = '<?php echo Yii::t('app', 'BUTTON_DELETE'); ?>' ;
                                div_del.innerHTML = '<i class="fa-solid fa-trash"></i>';
                                div_content_event.append(div_del);

                                var div_edit = document.createElement('div');
                                div_edit.id = 'node_edit_' + data['id'];
                                div_edit.className = 'edit edit-event glyphicon-pencil' ;
                                div_edit.title = '<?php echo Yii::t('app', 'BUTTON_EDIT'); ?>' ;
                                div_edit.innerHTML = '<i class="fa-solid fa-pen"></i>';
                                div_content_event.append(div_edit);

                                var div_add_parameter = document.createElement('div');
                                div_add_parameter.id = 'node_add_parameter_' + data['id'];
                                div_add_parameter.className = 'param add-parameter glyphicon-plus' ;
                                div_add_parameter.title = '<?php echo Yii::t('app', 'BUTTON_ADD'); ?>';
                                div_add_parameter.innerHTML = '<i class="fa-solid fa-plus"></i>';
                                div_content_event.append(div_add_parameter);

                                var div_show_comment = document.createElement('div');
                                div_show_comment .id = 'node_show_comment_' + data['id'];
                                div_show_comment .className = 'show-event-comment glyphicon-paperclip' ;
                                div_show_comment .title = '<?php echo Yii::t('app', 'BUTTON_COMMENT'); ?>' ;
                                div_show_comment.innerHTML = '<i class="fa-solid fa-paperclip"></i>';
                                div_content_event.append(div_show_comment );

                                var div_copy = document.createElement('div');
                                div_copy.id = 'node_copy_' + data['id'];
                                div_copy.className = 'copy-event glyphicon-plus-sign' ;
                                div_copy.title = '<?php echo Yii::t('app', 'BUTTON_COPY'); ?>' ;
                                div_copy.innerHTML = '<i class="fa-solid fa-circle-plus"></i>';
                                div_content_event.append(div_copy);
                            }

                            document.getElementById('add-event-form').reset();

                            //применяем к новым элементам свойства plumb
                            //находим DOM элемент description уровня (идентификатор div level_description)
                            var div_level_id = document.getElementById('level_description_'+ data['id_level']);
                            var g_name = 'group'+ data['id_level']; //определяем имя группы
                            var grp = instance.getGroup(g_name);//определяем существует ли группа с таким именем
                            if (grp == 0){
                                //если группа не существует то создаем группу с определенным именем group_name
                                instance.addGroup({
                                    el: div_level_id,
                                    id: g_name,
                                    draggable: false, //перетаскивание группы
                                    //constrain: true, //запрет на перетаскивание элементов за группу (false перетаскивать можно)
                                    dropOverride:true,
                                });
                            }
                            //находим DOM элемент node (идентификатор div node)
                            var div_node_id = document.getElementById('node_'+ data['id']);
                            //делаем node перетаскиваемым
                            instance.draggable(div_node_id);
                            //добавляем элемент div_node_id в группу с именем group_name
                            instance.addToGroup(g_name, div_node_id);


                            instance.makeSource(div_node_id, {
                                filter: ".fa-share",
                                anchor: "Bottom",
                            });

                            instance.makeTarget(div_node_id, {
                                dropOptions: { hoverClass: "dragHover" },
                                anchor: "Top",
                                allowLoopback: false, // Нельзя создать кольцевую связь
                                maxConnections: 1,
                                onMaxConnections: function (info, e) {
                                    var message = "<?php echo Yii::t('app', 'MAXIMUM_CONNECTIONS'); ?>" + info.maxConnections;
                                    document.getElementById("message-text").lastChild.nodeValue = message;
                                    $("#viewMessageErrorLinkingItemsModalForm").modal("show");
                                }
                            });


                            var level = parseInt(data['id_level'], 10);
                            var node = data['id'];
                            var name = data['name'];
                            var parent_node = data['parent_node'];
                            var description = data['description'];
                            var certainty_factor = data['certainty_factor'];
                            var removed = sequence_mas.push([level, node]);

                            var j = 0;
                            $.each(mas_data_node, function (i, elem) {
                                j = j + 1;
                            });
                            mas_data_node[j] = {id:node, parent_node:parent_node, name:name, description:description, certainty_factor:certainty_factor,};

                            // Выключение переходов на модальные окна
                            var nav_add_event = document.getElementById('nav_add_event');
                            if ((data['type'] == <?= Node::INITIAL_EVENT_TYPE ?>) && (data['level_count'] == 1) && (data['mode'] == <?= TreeDiagram::EXTENDED_TREE_MODE ?>)){
                                nav_add_event.className = 'disabled dropdown-item';
                                nav_add_event.setAttribute("data-bs-target", "");
                            }

                            $.pjax.reload({container: '#pjax_event_editor'});
                            $.pjax.xhr = null;

                            // Скрывание модального окна
                            $("#addEventModalForm").modal("hide");
                        } else {
                            // Отображение ошибок ввода
                            viewErrors("#add-event-form", data);
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
    'id' => 'add-event-form',
    'enableClientValidation' => true,
    'errorSummaryCssClass' => 'error-summary',
]); ?>

<?= $form->errorSummary($node_model); ?>

<?= $form->field($node_model, 'name')->textarea(['maxlength' => true, 'rows'=>1]) ?>

<?= $form->field($node_model, 'certainty_factor')->textarea(['maxlength' => true, 'rows'=>1]) ?>

<?= $form->field($node_model, 'description')->textarea(['maxlength' => true, 'rows'=>6]) ?>

<?php if ((TreeDiagram::CLASSIC_TREE_MODE == $model_tree_diagram->mode) or ($the_initial_event_is == 0) ){ ?>
    <?= $form->field($node_model, 'level_id')->dropDownList($array_levels)->
                    label(Yii::t('app', 'NODE_MODEL_LEVEL_ID'), ['id' => 'add_label_level']); ?>
<?php } else { ?>
    <?= $form->field($node_model, 'level_id')->dropDownList($array_levels_initial_without)->
                    label(Yii::t('app', 'NODE_MODEL_LEVEL_ID'), ['id' => 'add_label_level']); ?>
<?php } ?>

<?= Button::widget([
    'label' => Yii::t('app', 'BUTTON_ADD'),
    'options' => [
        'id' => 'add-event-button',
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




<!-- Модальное окно изменения нового события -->
<?php Modal::begin([
    'id' => 'editEventModalForm',
    'title' => '<h3>' . Yii::t('app', 'EVENT_EDIT_EVENT') . '</h3>',
]); ?>

<!-- Скрипт модального окна -->
<script type="text/javascript">
    // Выполнение скрипта при загрузке страницы
    $(document).ready(function() {
        // Обработка нажатия кнопки сохранения
        $("#edit-event-button").click(function(e) {
            e.preventDefault();
            var form = $("#edit-event-form");
            // Ajax-запрос
            $.ajax({
                //переход на экшен левел
                url: "<?= Yii::$app->request->baseUrl . '/' . Lang::getCurrent()->url .
                '/tree-diagrams/edit-event'?>",
                type: "post",
                data: form.serialize() + "&node_id_on_click=" + node_id_on_click + "&level_id_on_click=" + level_id_on_click,
                dataType: "json",
                success: function(data) {
                    // Если валидация прошла успешно (нет ошибок ввода)
                    if (data['success']) {
                        // Скрывание модального окна
                        $("#editEventModalForm").modal("hide");

                        $.each(mas_data_node, function (i, elem) {
                            if (elem.id == data['id']){
                                mas_data_node[i].name = data['name'];
                                mas_data_node[i].description = data['description'];
                                mas_data_node[i].certainty_factor = data['certainty_factor'];
                            }
                        });

                        if (level_id_on_click == data['id_level']){
                            var div_event_name = document.getElementById('node_name_' + data['id']);
                            if ((data['certainty_factor'] == "")||(data['certainty_factor'] == 0)){
                                div_event_name.innerHTML = data['name'];
                            } else {
                                div_event_name.innerHTML = data['name'] + ' (' + data['certainty_factor'] + ')';
                            }
                        } else {
                            var div_event = document.getElementById('node_' + data['id']);
                            var new_div_event = div_event.cloneNode(true); // клонировать сообщение
                            var div_level_layer = document.getElementById('level_description_'+ data['id_level']);
                            instance.removeFromGroup(div_event);

                            // удаляем старый node
                            instance.remove(div_event);

                            div_level_layer.append(new_div_event); // разместить клонированный элемент в новый уровень

                            //разместить новый node по новым координатам
                            new_div_event.style.left = '50px';
                            new_div_event.style.top = '50px';

                            var div_event_name = document.getElementById('node_name_' + data['id']);
                            if ((data['certainty_factor'] == "")||(data['certainty_factor'] == 0)){
                                div_event_name.innerHTML = data['name'];
                            } else {
                                div_event_name.innerHTML = data['name'] + ' (' + data['certainty_factor'] + ')';
                            }

                            //делаем новый node перетаскиваемым
                            instance.draggable(new_div_event);

                            //добавляем элемент new_div_event в группу с именем g_name
                            //находим DOM элемент description уровня (идентификатор div level_description)
                            var div_level_id = document.getElementById('level_description_'+ data['id_level']);
                            var g_name = 'group'+ data['id_level']; //определяем имя группы
                            var grp = instance.getGroup(g_name);//определяем существует ли группа с таким именем
                            if (grp == 0){
                                //если группа не существует то создаем группу с определенным именем group_name
                                instance.addGroup({
                                    el: div_level_id,
                                    id: g_name,
                                    draggable: false, //перетаскивание группы
                                    //constrain: true, //запрет на перетаскивание элементов за группу (false перетаскивать можно)
                                    dropOverride:true,
                                });
                            }
                            instance.addToGroup(g_name, new_div_event);//добавляем в группу

                            instance.makeSource(new_div_event, {
                                filter: ".fa-share",
                                anchor: "Bottom",
                            });

                            instance.makeTarget(new_div_event, {
                                dropOptions: { hoverClass: "dragHover" },
                                anchor: "Top",
                                allowLoopback: false, // Нельзя создать кольцевую связь
                                maxConnections: 1,
                                onMaxConnections: function (info, e) {
                                    var message = "<?php echo Yii::t('app', 'MAXIMUM_CONNECTIONS'); ?>" + info.maxConnections;
                                    document.getElementById("message-text").lastChild.nodeValue = message;
                                    $("#viewMessageErrorLinkingItemsModalForm").modal("show");
                                }
                            });

                            //поиск комментария
                            var div_comment = document.getElementById('node_comment_' + data['id']);
                            if (div_comment != null){
                                var new_div_comment = div_comment.cloneNode(true); // клонировать сообщение
                                instance.removeFromGroup(div_comment);
                                instance.remove(div_comment);
                                div_level_layer.append(new_div_comment); // разместить клонированный элемент в новый уровень

                                //делаем новый node перетаскиваемым
                                instance.draggable(new_div_comment);

                                instance.addToGroup(g_name, new_div_comment);//добавляем в группу
                            }

                            //редактируем массив mas_data_node (чистим от удаляемого элемента)
                            $.each(mas_data_node, function (i, elem_node) {
                                //убираем входящие
                                if (data['id'] == elem_node.id){
                                    mas_data_node[i].parent_node = null;
                                }
                                //убираем изходящие
                                if (data['id'] == elem_node.parent_node){
                                    mas_data_node[i].parent_node = null;
                                }
                            });

                            //заносим изменения в массив sequence_mas
                            var level = parseInt(data['id_level'], 10);
                            var node = data['id'];
                            var pos_i = 0;
                            $.each(sequence_mas, function (i, mas) {
                                $.each(mas, function (j, elem) {
                                    //второй элемент это id узла события или механизма
                                    if (j == 1) {
                                        if (elem == node){
                                            pos_i = i;
                                        }
                                    };
                                });
                            });
                            sequence_mas[pos_i] = [level, node];
                        }
                        document.getElementById('edit-event-form').reset();
                    } else {
                        // Отображение ошибок ввода
                        viewErrors("#edit-event-form", data);
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
    'id' => 'edit-event-form',
    'enableClientValidation' => true,
    'errorSummaryCssClass' => 'error-summary',
]); ?>

<?= $form->errorSummary($node_model); ?>

<?= $form->field($node_model, 'name')->textarea(['maxlength' => true, 'rows'=>1]) ?>

<?= $form->field($node_model, 'certainty_factor')->textarea(['maxlength' => true, 'rows'=>1]) ?>

<?= $form->field($node_model, 'description')->textarea(['maxlength' => true, 'rows'=>6]) ?>

<?= $form->field($node_model, 'level_id')->dropDownList($array_levels)->label(Yii::t('app', 'NODE_MODEL_LEVEL_ID'), ['id' => 'edit_label_level']); ?>

<div id="alert_event_level_id" style="display:none;" class="alert-warning alert">
    <?php echo Yii::t('app', 'ALERT_CHANGE_LEVEL'); ?>
</div>

<?= Button::widget([
    'label' => Yii::t('app', 'BUTTON_SAVE'),
    'options' => [
        'id' => 'edit-event-button',
        'class' => 'btn-success',
        'style' => 'margin:5px'
    ]
]); ?>

<button type="button" class="btn btn-danger" data-bs-dismiss="modal"><?php echo Yii::t('app', 'BUTTON_CANCEL')?></button>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>




<!-- Модальное окно удаления события -->
<?php Modal::begin([
    'id' => 'deleteEventModalForm',
    'title' => '<h3>' . Yii::t('app', 'EVENT_DELETE_EVENT') . '</h3>',
]); ?>

<!-- Скрипт модального окна -->
<script type="text/javascript">
    // Выполнение скрипта при загрузке страницы
    $(document).ready(function() {
        // Обработка нажатия кнопки сохранения
        $("#delete-event-button").click(function(e) {
            e.preventDefault();
            // Ajax-запрос
            $.ajax({
                //переход на экшен левел
                url: "<?= Yii::$app->request->baseUrl . '/' . Lang::getCurrent()->url .
                '/tree-diagrams/delete-event'?>",
                type: "post",
                data: "YII_CSRF_TOKEN=<?= Yii::$app->request->csrfToken ?>" + "&node_id_on_click=" + node_id_on_click,
                dataType: "json",
                success: function(data) {
                    // Если валидация прошла успешно (нет ошибок ввода)
                    if (data['success']) {
                        // Скрывание модального окна
                        $("#deleteEventModalForm").modal("hide");
                        var div_del_event = document.getElementById('node_' + node_id_on_click);
                        instance.removeFromGroup(div_del_event);//удаляем из группы
                        instance.remove(div_del_event);// удаляем node

                        //поиск комментария
                        var div_comment = document.getElementById('node_comment_' + node_id_on_click);
                        if (div_comment != null){
                            instance.removeFromGroup(div_comment);
                            instance.remove(div_comment);
                        }

                        //редактируем массив mas_data_node (чистим от удаляемого элемента)
                        //убираем соединения от удаляемого элемента
                        var temporary_mas_data_node = {};
                        var q = 0;
                        $.each(mas_data_node, function (i, elem_node) {
                            //убираем входящие
                            if (node_id_on_click != elem_node.id){//убираем элемент
                                //убираем изходящие
                                if (node_id_on_click == elem_node.parent_node){
                                    temporary_mas_data_node[q] = {
                                        "id":elem_node.id,
                                        "parent_node":null,
                                        "name":elem_node.name,
                                        "description":elem_node.description,
                                        "certainty_factor":elem_node.certainty_factor,
                                    };
                                    q = q+1;
                                } else {
                                    temporary_mas_data_node[q] = {
                                        "id":elem_node.id,
                                        "parent_node":elem_node.parent_node,
                                        "name":elem_node.name,
                                        "description":elem_node.description,
                                        "certainty_factor":elem_node.certainty_factor,
                                    };
                                    q = q+1;
                                }
                            }
                        });
                        mas_data_node = temporary_mas_data_node;

                        //заносим изменения в массив sequence_mas
                        var pos_i = 0;
                        $.each(sequence_mas, function (i, mas) {
                            $.each(mas, function (j, elem) {
                                //второй элемент это id узла события или механизма
                                if (j == 1) {
                                    if (elem == node_id_on_click){
                                        pos_i = i;
                                    }
                                };
                            });
                        });
                        sequence_mas.splice(pos_i, 1);

                        // Включение переходов на модальные окна
                        var nav_add_event = document.getElementById('nav_add_event');
                        if (data['type'] == <?= Node::INITIAL_EVENT_TYPE ?>){
                            nav_add_event.className = 'dropdown-item';
                            nav_add_event.setAttribute("data-bs-target", "#addEventModalForm");
                        }

                        $.pjax.reload({container: '#pjax_event_editor'});
                        $.pjax.xhr = null;
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
    'id' => 'delete-event-form',
]); ?>

<div class="modal-body">
    <p style="font-size: 14px">
        <?php echo Yii::t('app', 'DELETE_EVENT_TEXT'); ?>
    </p>
</div>

<?= Button::widget([
    'label' => Yii::t('app', 'BUTTON_DELETE'),
    'options' => [
        'id' => 'delete-event-button',
        'class' => 'btn-success',
        'style' => 'margin:5px'
    ]
]); ?>

<button type="button" class="btn btn-danger" data-bs-dismiss="modal"><?php echo Yii::t('app', 'BUTTON_CANCEL')?></button>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>




<!-- Модальное окно копирования события на уровень -->
<?php Modal::begin([
    'id' => 'copyEventModalForm',
    'title' => '<h3>' . Yii::t('app', 'EVENT_COPY_EVENT') . '</h3>',
]); ?>

<!-- Скрипт модального окна -->
<script type="text/javascript">
    // Выполнение скрипта при загрузке страницы
    $(document).ready(function() {
        // Обработка нажатия кнопки сохранения
        $("#copy-event-button").click(function(e) {
            e.preventDefault();
            var form = $("#copy-event-form");
            // Ajax-запрос
            $.ajax({
                //переход на экшен левел
                url: "<?= Yii::$app->request->baseUrl . '/' . Lang::getCurrent()->url .
                '/tree-diagrams/copy-event-to-level'?>",
                type: "post",
                data: form.serialize() + "&node_id_on_click=" + node_id_on_click + "&level_id_on_click=" + level_id_on_click,
                dataType: "json",
                success: function(data) {
                    // Если валидация прошла успешно (нет ошибок ввода)
                    if (data['success']) {
                        // Скрывание модального окна
                        $("#copyEventModalForm").modal("hide");

                        var div_level_layer = document.getElementById('level_description_' + data['id_level']);

                        var div_event = document.createElement('div');
                        div_event.id = 'node_' + data['id'];
                        div_event.className = 'div-event node';
                        div_level_layer.append(div_event);

                        var div_content_event = document.createElement('div');
                        div_content_event.className = 'content-event';
                        div_event.append(div_content_event);

                        var div_event_name = document.createElement('div');
                        div_event_name.id = 'node_name_' + data['id'];
                        div_event_name.className = 'div-event-name' ;
                        if ((data['certainty_factor'] == "")||(data['certainty_factor'] == 0)||(data['certainty_factor'] == null)){
                            div_event_name.innerHTML = data['name'];
                        } else {
                            div_event_name.innerHTML = data['name'] + ' (' + data['certainty_factor'] + ')';
                        }
                        div_content_event.append(div_event_name);

                        var div_ep = document.createElement('div');
                        div_ep.className = 'ep ep-event' ;
                        div_ep.title = '<?php echo Yii::t('app', 'BUTTON_CONNECTION'); ?>' ;
                        div_ep.innerHTML = '<i class="fa-solid fa-share"></i>';
                        div_content_event.append(div_ep);

                        var div_del = document.createElement('div');
                        div_del.id = 'node_del_' + data['id'];
                        div_del.className = 'del del-event glyphicon-trash' ;
                        div_del.title = '<?php echo Yii::t('app', 'BUTTON_DELETE'); ?>' ;
                        div_del.innerHTML = '<i class="fa-solid fa-trash"></i>';
                        div_content_event.append(div_del);

                        var div_edit = document.createElement('div');
                        div_edit.id = 'node_edit_' + data['id'];
                        div_edit.className = 'edit edit-event glyphicon-pencil' ;
                        div_edit.title = '<?php echo Yii::t('app', 'BUTTON_EDIT'); ?>' ;
                        div_edit.innerHTML = '<i class="fa-solid fa-pen"></i>';
                        div_content_event.append(div_edit);

                        var div_add_parameter = document.createElement('div');
                        div_add_parameter.id = 'node_add_parameter_' + data['id'];
                        div_add_parameter.className = 'param add-parameter glyphicon-plus' ;
                        div_add_parameter.title = '<?php echo Yii::t('app', 'BUTTON_ADD'); ?>';
                        div_add_parameter.innerHTML = '<i class="fa-solid fa-plus"></i>';
                        div_content_event.append(div_add_parameter);

                        var div_show_comment = document.createElement('div');
                        div_show_comment.id = 'node_show_comment_' + data['id'];
                        div_show_comment.className = 'show-event-comment glyphicon-paperclip' ;
                        div_show_comment.title = '<?php echo Yii::t('app', 'BUTTON_COMMENT'); ?>' ;
                        div_show_comment.innerHTML = '<i class="fa-solid fa-paperclip"></i>';
                        div_content_event.append(div_show_comment);

                        var div_copy = document.createElement('div');
                        div_copy.id = 'node_copy_' + data['id'];
                        div_copy.className = 'copy-event glyphicon-plus-sign' ;
                        div_copy.title = '<?php echo Yii::t('app', 'BUTTON_COPY'); ?>' ;
                        div_copy.innerHTML = '<i class="fa-solid fa-circle-plus"></i>';
                        div_content_event.append(div_copy);


                        //применяем к новым элементам свойства plumb
                        //находим DOM элемент description уровня (идентификатор div level_description)
                        var div_level_id = document.getElementById('level_description_'+ data['id_level']);
                        var g_name = 'group'+ data['id_level']; //определяем имя группы
                        var grp = instance.getGroup(g_name);//определяем существует ли группа с таким именем
                        if (grp == 0){
                            //если группа не существует то создаем группу с определенным именем group_name
                            instance.addGroup({
                                el: div_level_id,
                                id: g_name,
                                draggable: false, //перетаскивание группы
                                //constrain: true, //запрет на перетаскивание элементов за группу (false перетаскивать можно)
                                dropOverride:true,
                            });
                        }
                        //находим DOM элемент node (идентификатор div node)
                        var div_node_id = document.getElementById('node_'+ data['id']);
                        //делаем node перетаскиваемым
                        instance.draggable(div_node_id);
                        //добавляем элемент div_node_id в группу с именем group_name
                        instance.addToGroup(g_name, div_node_id);


                        instance.makeSource(div_node_id, {
                            filter: ".fa-share",
                            anchor: "Bottom",
                        });

                        instance.makeTarget(div_node_id, {
                            dropOptions: { hoverClass: "dragHover" },
                            anchor: "Top",
                            allowLoopback: false, // Нельзя создать кольцевую связь
                            maxConnections: 1,
                            onMaxConnections: function (info, e) {
                                var message = "<?php echo Yii::t('app', 'MAXIMUM_CONNECTIONS'); ?>" + info.maxConnections;
                                document.getElementById("message-text").lastChild.nodeValue = message;
                                $("#viewMessageErrorLinkingItemsModalForm").modal("show");
                            }
                        });


                        var level = parseInt(data['id_level'], 10);
                        var node = data['id'];
                        var name = data['name'];
                        var parent_node = data['parent_node'];
                        var description = data['description'];
                        var certainty_factor = data['certainty_factor'];
                        var removed = sequence_mas.push([level, node]);

                        var j = 0;
                        $.each(mas_data_node, function (i, elem) {
                            j = j + 1;
                        });
                        mas_data_node[j] = {id:node, parent_node:parent_node, name:name, description:description, certainty_factor:certainty_factor,};

                        $.pjax.reload({container: '#pjax_event_editor'});
                        $.pjax.xhr = null;


                        //добавляем параметры для копируемого события
                        for (var i = 0; i < data['i']; i++) {
                            //добавляем div разделительной линии для первого события
                            if (i == 0){
                                //добавляем div разделительной линии
                                var div_line = document.createElement('div');
                                div_line.id = 'line_' + data['id'];
                                div_line.className = 'div-line';
                                div_event.append(div_line);
                            }

                            var div_parameter = document.createElement('div');
                            div_parameter.id = 'parameter_' + data['parameter_id_'+i];
                            div_parameter.className = 'div-parameter';
                            div_parameter.innerHTML = data['parameter_name_'+i] + " " + data['parameter_operator_name_'+i] + " " + data['parameter_value_'+i];
                            div_event.append(div_parameter);

                            var div_button_parameter = document.createElement('div');
                            div_button_parameter.className = 'button-parameter';
                            div_parameter.prepend(div_button_parameter);

                            var div_edit_parameter = document.createElement('div');
                            div_edit_parameter.id = 'edit_parameter_' + data['parameter_id_'+i];
                            div_edit_parameter.className = 'edit edit-parameter glyphicon-pencil';
                            div_edit_parameter.title = '<?php echo Yii::t('app', 'BUTTON_EDIT'); ?>' ;
                            div_edit_parameter.innerHTML = '<i class="fa-solid fa-pen"></i>';
                            div_button_parameter.append(div_edit_parameter);

                            var div_del_parameter = document.createElement('div');
                            div_del_parameter.id = 'del_parameter_' + data['parameter_id_'+i];
                            div_del_parameter.className = 'del del-parameter glyphicon-trash';
                            div_del_parameter.title = '<?php echo Yii::t('app', 'BUTTON_DELETE'); ?>' ;
                            div_del_parameter.innerHTML = '<i class="fa-solid fa-trash"></i>';
                            div_button_parameter.append(div_del_parameter);


                            var id = data['parameter_id_'+i];
                            var name = data['parameter_name_'+i];
                            var description = data['parameter_description_'+i];
                            var operator = data['parameter_operator_'+i];
                            var value = data['parameter_value_'+i];

                            var j = 0;
                            $.each(mas_data_parameter, function (i, elem) {
                                j = j + 1;
                            });
                            mas_data_parameter[j] = {id:id, name:name, description:description, operator:operator, value:value};
                        }





                        document.getElementById('copy-event-form').reset();
                    } else {
                        // Отображение ошибок ввода
                        viewErrors("#copy-event-form", data);
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
    'id' => 'copy-event-form',
    'enableClientValidation' => true,
    'errorSummaryCssClass' => 'error-summary',
]); ?>

<?= $form->errorSummary($node_model); ?>

<?= $form->field($node_model, 'level_id')->dropDownList($array_levels_initial_without)->label(Yii::t('app', 'NODE_MODEL_LEVEL_ID'), ['id' => 'copy_label_level']); ?>

<div id="alert_event_level_id" style="display:none;" class="alert-warning alert">
    <?php echo Yii::t('app', 'ALERT_CHANGE_LEVEL'); ?>
</div>

<?= Button::widget([
    'label' => Yii::t('app', 'BUTTON_SAVE'),
    'options' => [
        'id' => 'copy-event-button',
        'class' => 'btn-success',
        'style' => 'margin:5px'
    ]
]); ?>

<button type="button" class="btn btn-danger" data-bs-dismiss="modal"><?php echo Yii::t('app', 'BUTTON_CANCEL')?></button>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>