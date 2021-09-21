<?php

use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
use yii\bootstrap\Button;
use app\modules\main\models\Lang;
use app\modules\stde\models\TransitionProperty;

/* @var $transition_model app\modules\stde\models\Transition */

?>

<!-- Модальное окно добавления нового перехода -->
<?php Modal::begin([
    'id' => 'addTransitionModalForm',
    'header' => '<h3>' . Yii::t('app', 'TRANSITION_ADD_NEW_TRANSITION') . '</h3>',
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
                        // Скрывание модального окна
                        $("#addTransitionModalForm").modal("hide");

                        //присваиваем наименование и свойства новой связи
                        instance.select(current_connection).setLabel({
                            label: data['name'],
                            location: 0.5, //расположение посередине
                            cssClass: "transitions-style"
                        });

                        //создаем параметр для новой связи id_transition куда прописываем название связи transition_" +  data['id'] (как замена id)
                        instance.select(current_connection).setParameter('id_transition',"transition_connect_" +  data['id']);

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
                        div_content_transition.append(div_del);

                        var div_edit = document.createElement('div');
                        div_edit.id = 'transition_edit_' + data['id'];
                        div_edit.className = 'edit-transition glyphicon-pencil' ;
                        div_edit.title = '<?php echo Yii::t('app', 'BUTTON_EDIT'); ?>' ;
                        div_content_transition.append(div_edit);

                        var div_hide = document.createElement('div');
                        div_hide.id = 'transition_hide_' + data['id'];
                        div_hide.className = 'hide-transition glyphicon-eye-close' ;
                        div_hide.title = '<?php echo Yii::t('app', 'BUTTON_HIDE'); ?>' ;
                        div_content_transition.append(div_hide);

                        var div_add_property = document.createElement('div');
                        div_add_property.id = 'transition_add_property_' + data['id'];
                        div_add_property.className = 'add-transition-property glyphicon-plus' ;
                        div_add_property.title = '<?php echo Yii::t('app', 'BUTTON_ADD'); ?>';
                        div_content_transition.append(div_add_property);


                        var div_transition_property_name = document.createElement('div');
                        div_transition_property_name.id = 'transition_property_' + data['id_property'];
                        div_transition_property_name.className = 'div-transition-property' ;
                        div_transition_property_name.innerHTML = data['name_property'] + " " + data['operator_property'] + " " + data['value_property'];
                        div_transition.append(div_transition_property_name);

                        var div_button_transition_property = document.createElement('div');
                        div_button_transition_property.className = 'button-transition-property';
                        div_transition_property_name.append(div_button_transition_property);

                        var div_edit_transition_property = document.createElement('div');
                        div_edit_transition_property.id = 'transition_property_edit_' + data['id_property'];
                        div_edit_transition_property.className = 'edit-transition-property glyphicon-pencil';
                        div_edit_transition_property.title = '<?php echo Yii::t('app', 'BUTTON_EDIT'); ?>' ;
                        div_button_transition_property.append(div_edit_transition_property);

                        var div_del_transition_property = document.createElement('div');
                        div_del_transition_property.id = 'transition_property_del_' + data['id_property'];
                        div_del_transition_property.className = 'del-transition-property glyphicon-trash';
                        div_del_transition_property.title = '<?php echo Yii::t('app', 'BUTTON_DELETE'); ?>' ;
                        div_button_transition_property.append(div_del_transition_property);


                        //сделать div двигаемым
                        var div_transition = document.getElementById('transition_' + data['id']);
                        instance.draggable(div_transition);



                        //---------добавить новое в массивы связи и условий для изменений



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
]); ?>


<?= $form->errorSummary($transition_model); ?>

<?= $form->field($transition_model, 'name')->textInput(['maxlength' => true]) ?>

<?= $form->field($transition_model, 'description')->textarea(['maxlength' => true, 'rows'=>6]) ?>

<?= $form->field($transition_model, 'name_property')->textInput(['maxlength' => true]) ?>

<?= $form->field($transition_model, 'description_property')->textarea(['maxlength' => true, 'rows'=>6]) ?>

<?= $form->field($transition_model, 'operator_property')->dropDownList(TransitionProperty::getOperatorArray()) ?>

<?= $form->field($transition_model, 'value_property')->textInput(['maxlength' => true]) ?>


<?= Button::widget([
    'label' => Yii::t('app', 'BUTTON_SAVE'),
    'options' => [
        'id' => 'add-transition-button',
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