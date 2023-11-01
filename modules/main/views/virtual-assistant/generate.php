<?php

use app\modules\main\models\VirtualAssistant;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap5\ActiveForm;
use app\modules\main\models\GeneratorForm;
use yii\bootstrap5\Button;
use app\modules\main\models\Lang;
use yii\bootstrap5\ButtonDropdown;

/** @var yii\web\View $this */
/** @var app\modules\main\models\VirtualAssistant $model */

$this->title = Yii::t('app', 'VIRTUAL_ASSISTANT_PAGE_VIRTUAL_ASSISTANT') . ': ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'VIRTUAL_ASSISTANT_PAGE_VIRTUAL_ASSISTANTS'),
    'url' => ['list']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<?= $this->render('_modal_form', ['model' => $model]); ?>

<!-- Подключение стилей -->
<?php
$this->registerCssFile('/css/virtual-assistant.css', ['position'=>yii\web\View::POS_HEAD]);
?>


<!-- Скрипт генератора -->
<script type="text/javascript">
    // Выполнение скрипта при загрузке страницы
    $(document).ready(function() {
        // Обработка нажатия кнопки сохранения
        $("#generator-button").click(function(e) {
            e.preventDefault();
            var form = $("#generator-form");
            // Ajax-запрос
            $.ajax({
                //переход на экшен левел
                url: "<?= Yii::$app->request->baseUrl . '/' . Lang::getCurrent()->url .
                '/virtual-assistant/generate-platform/' . $model->id ?>",
                type: "post",
                data: form.serialize(),
                dataType: "json",
                success: function(data) {
                    // Если валидация прошла успешно (нет ошибок ввода)
                    if (data['success']) {
                        // Включение кнопок загрузок
                        var button1 = document.getElementById('download-json-button');
                        var button2 = document.getElementById('download-csv-button');
                        var button3 = document.getElementById('download-json2-button');
                        button1.className = 'btn btn-primary btn-sm button';
                        button2.className = 'btn btn-primary btn-sm button';
                        button3.className = 'btn btn-primary btn-sm button';

                        document.getElementById('generator-form').reset();
                    } else {
                        // Отображение ошибок ввода
                        viewErrors("#generator-form", data);
                    }
                },
                error: function() {
                    alert('Error!');
                }
            });
        });


        // Обработка нажатия кнопки загрузки
        $("#download-json-button").click(function(e) {
            e.preventDefault();
            // Ajax-запрос
            $.ajax({
                //переход на экшен левел
                url: "<?= Yii::$app->request->baseUrl . '/' . Lang::getCurrent()->url .
                '/virtual-assistant/download-json/' . $model->id ?>",
                type: "post",
                data: "YII_CSRF_TOKEN=<?= Yii::$app->request->csrfToken ?>",
                dataType: "json",
                success: function(data) {
                    if (data['success']) {
                        var link = document.createElement('a');
                        link.setAttribute('href','/' + data['fileName']);
                        link.setAttribute('download','json-file1.json');
                        link.click();
                    }
                },
                error: function() {
                    alert('Error!');
                }
            });
        });

        // Обработка нажатия кнопки загрузки
        $("#download-csv-button").click(function(e) {
            e.preventDefault();
            // Ajax-запрос
            $.ajax({
                //переход на экшен левел
                url: "<?= Yii::$app->request->baseUrl . '/' . Lang::getCurrent()->url .
                '/virtual-assistant/download-csv/' . $model->id ?>",
                type: "post",
                data: "YII_CSRF_TOKEN=<?= Yii::$app->request->csrfToken ?>",
                dataType: "json",
                success: function(data) {
                    if (data['success']) {
                        var link = document.createElement('a');
                        link.setAttribute('href','/' + data['fileName']);
                        link.setAttribute('download','csv-file.csv');
                        link.click();
                    }
                },
                error: function() {
                    alert('Error!');
                }
            });
        });

        // Обработка нажатия кнопки загрузки
        $("#download-json2-button").click(function(e) {
            e.preventDefault();
            // Ajax-запрос
            $.ajax({
                //переход на экшен левел
                url: "<?= Yii::$app->request->baseUrl . '/' . Lang::getCurrent()->url .
                '/virtual-assistant/download-json2/' . $model->id ?>",
                type: "post",
                data: "YII_CSRF_TOKEN=<?= Yii::$app->request->csrfToken ?>",
                dataType: "json",
                success: function(data) {
                    if (data['success']) {
                        var link = document.createElement('a');
                        link.setAttribute('href','/' + data['fileName']);
                        link.setAttribute('download','json-file2.json');
                        link.click();
                    }
                },
                error: function() {
                    alert('Error!');
                }
            });
        });
    });
</script>




<div class="virtual-assistant-generator">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">

        <div class="col">
            <div class="step">
                <h5><?php echo Yii::t('app', 'STEP_1');?></h5>

                <?= Html::a(Yii::t('app', 'BUTTON_DIALOGUE_MODEL'), ['open-dialogue-model', 'id' => $model->id], ['class' => 'btn btn-primary btn-sm button']) ?>
                <br><br>
                <?= ButtonDropdown::widget([
                    'label' => Yii::t('app', 'BUTTON_KNOWLEDGE_BASE_MODEL'),
                    'encodeLabel' => false,
                    'buttonOptions' => [
                        'class' => 'btn btn-primary btn-sm button',
                        'style' => 'white-space: normal',
                    ],
                    'dropdown' => [
                        'items' => $array_knowledge_base_model,
                    ],
                ]); ?>
                <br><br>
                <?= ButtonDropdown::widget([
                    'label' => Yii::t('app', 'BUTTON_CONVERSATIONAL_INTERFACE_MODEL'),
                    'encodeLabel' => false,
                    'buttonOptions' => [
                        'class' => 'btn btn-primary btn-sm button',
                        'style' => 'white-space: normal',
                    ],
                    'dropdown' => [
                        'items' => $array_conversational_interface_model,
                    ],
                ]); ?>
            </div>
        </div>


        <div class="col">
            <div class="step">
                <h5><?php echo Yii::t('app', 'STEP_2');?></h5>

                <?php $form = ActiveForm::begin([
                    'id' => 'generator-form',
                ]); ?>

                <div class="select-template">
                    <?= $form->field($generator, 'platform')->dropDownList(GeneratorForm::getPlatformsArray())->
                    label(Yii::t('app', 'GENERATOR_FORM_PLATFORM'),['style'=>'margin-left:-70%']) ?>
                </div>

                <?= Button::widget([
                    'label' => Yii::t('app', 'BUTTON_GENERATE'),
                    'options' => [
                        'id' => 'generator-button',
                        'class' => 'btn btn-success btn-sm button',
                        'style' => 'margin:10px'
                    ]
                ]); ?>

                <?php ActiveForm::end(); ?>
            </div>
        </div>


        <div class="col">
            <div class="step">
                <h5><?php echo Yii::t('app', 'STEP_3');?></h5>

                <?= Button::widget([
                    'label' => Yii::t('app', 'BUTTON_DOWNLOAD_JSON_1'),
                    'options' => [
                        'id' => 'download-json-button',
                        'class' => 'btn btn-primary btn-sm button disabled',
                        'style' => 'margin-bottom:20px'
                    ]
                ]); ?>
                <?= Button::widget([
                    'label' => Yii::t('app', 'BUTTON_DOWNLOAD_CSV'),
                    'options' => [
                        'id' => 'download-csv-button',
                        'class' => 'btn btn-primary btn-sm button disabled',
                        'style' => 'margin-bottom:20px'
                    ]
                ]); ?>
                <?= Button::widget([
                    'label' => Yii::t('app', 'BUTTON_DOWNLOAD_JSON_2'),
                    'options' => [
                        'id' => 'download-json2-button',
                        'class' => 'btn btn-primary btn-sm button disabled',
                        'style' => 'margin-bottom:20px'
                    ]
                ]); ?>

            </div>
        </div>

    </div>
</div>
