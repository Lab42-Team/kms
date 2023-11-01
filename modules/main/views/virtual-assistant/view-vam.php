<?php

use app\modules\main\models\VirtualAssistant;
use app\modules\main\models\VirtualAssistantModel;
use app\modules\main\models\Diagram;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\modules\main\models\VirtualAssistant $model */

$this->title = Yii::t('app', 'VIRTUAL_ASSISTANT_PAGE_VIRTUAL_ASSISTANT_MODEL') . ': ' . $model_vam->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'VIRTUAL_ASSISTANT_PAGE_VIRTUAL_ASSISTANTS'),
    'url' => ['list']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'VIRTUAL_ASSISTANT_PAGE_VIRTUAL_ASSISTANT') .
    ' - ' . $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<?= $this->render('_modal_form_vam', ['model' => $model, 'model_vam' => $model_vam,]); ?>

<div class="virtual-assistant-model-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('<i class="fa-solid fa-pencil"></i> ' .
            Yii::t('app', 'BUTTON_UPDATE'), ['update-vam', 'id' => $model->id, 'id_vam' => $model_vam->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('<i class="fa-solid fa-trash"></i> ' .
            Yii::t('app', 'BUTTON_DELETE'), ['#'], [
            'class' => 'btn btn-danger',
            'data-bs-toggle' => 'modal',
            'data-bs-target' => '#removeVirtualAssistantModelModalForm'
        ]) ?>

    </p>

    <?= DetailView::widget([
        'model' => $model_vam,
        'attributes' => [
            'id',
            [
                'attribute' => 'created_at',
                'format' => ['date', 'dd.MM.Y HH:mm:ss']
            ],
            [
                'attribute' => 'updated_at',
                'format' => ['date', 'dd.MM.Y HH:mm:ss']
            ],
            [
                'attribute' => 'dialogue_model',
                'format' => 'raw',
                'value' => function($data) {
                    return $data->dialogueModel->name;
                },
                'filter' => Diagram::getAllStateTransitionDiagramArray(),
                'filterInputOptions' => ['class' => 'form-select']
            ],
            [
                'attribute' => 'target_model',
                'format' => 'raw',
                'value' => function($data) {
                    return $data->targetModel->name;
                },
                'filter' => Diagram::getAllStateTransitionDiagramArray(),
                'filterInputOptions' => ['class' => 'form-select']
            ],
            [
                'attribute' => 'type',
                'format' => 'raw',
                'value' => function($data) {
                    return $data->getTypeName();
                },
                'filter' => VirtualAssistantModel::getTypesArray(),
            ],
        ],
    ]) ?>

</div>
