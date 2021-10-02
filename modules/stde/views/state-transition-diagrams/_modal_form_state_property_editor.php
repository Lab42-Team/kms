<?php

use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
use yii\bootstrap\Button;
use app\modules\main\models\Lang;
use app\modules\stde\models\StateProperty;

?>


<!-- Модальное окно добавления нового свойства состояния -->
<?php Modal::begin([
    'id' => 'addStatePropertyModalForm',
    'header' => '<h3>' . Yii::t('app', 'PROPERTY_ADD_NEW_PROPERTY') . '</h3>',
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

                        var div_property = document.createElement('div');
                        div_property.id = 'state_property_' + data['id'];
                        div_property.className = 'div-state-property';
                        div_property.innerHTML = data['name'] + " " + data['operator_name'] + " " + data['value'];
                        div_state.append(div_property);

                        var div_button_property = document.createElement('div');
                        div_button_property.className = 'button-state-property';
                        div_property.append(div_button_property);

                        var div_edit_property = document.createElement('div');
                        div_edit_property.id = 'state_property_edit_' + data['id'];
                        div_edit_property.className = 'edit-state-property glyphicon-pencil';
                        div_edit_property.title = '<?php echo Yii::t('app', 'BUTTON_EDIT'); ?>' ;
                        div_button_property.append(div_edit_property);

                        var div_del_property = document.createElement('div');
                        div_del_property.id = 'state_property_del_' + data['id'];
                        div_del_property.className = 'del-state-property glyphicon-trash';
                        div_del_property.title = '<?php echo Yii::t('app', 'BUTTON_DELETE'); ?>' ;
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
]); ?>


<?= $form->errorSummary($state_property_model); ?>

<?= $form->field($state_property_model, 'name')->textInput(['maxlength' => true]) ?>

<?= $form->field($state_property_model, 'description')->textarea(['maxlength' => true, 'rows'=>6]) ?>

<?= $form->field($state_property_model, 'operator')->dropDownList(StateProperty::getOperatorArray()) ?>

<?= $form->field($state_property_model, 'value')->textInput(['maxlength' => true]) ?>



<?= Button::widget([
    'label' => Yii::t('app', 'BUTTON_ADD'),
    'options' => [
        'id' => 'add-state-property-button',
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
