<?php

use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
use yii\bootstrap\Button;
use app\modules\main\models\Lang;

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