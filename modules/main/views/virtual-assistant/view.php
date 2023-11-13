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

$this->title = Yii::t('app', 'VIRTUAL_ASSISTANT_PAGE_VIRTUAL_ASSISTANT') . ': ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'VIRTUAL_ASSISTANT_PAGE_VIRTUAL_ASSISTANTS'),
    'url' => ['list']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<?= $this->render('_modal_form', ['model' => $model]); ?>

<div class="virtual-assistant-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('<i class="fa-solid fa-table"></i> ' .
            Yii::t('app', 'BUTTON_GENERATE_VA'), ['generate', 'id' => $model->id], ['class' => 'btn btn-success']
        ) ?>
        <?= Html::a('<i class="fa-solid fa-pencil"></i> ' .
            Yii::t('app', 'BUTTON_UPDATE'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('<i class="fa-solid fa-trash"></i> ' .
            Yii::t('app', 'BUTTON_DELETE'), ['#'], [
            'class' => 'btn btn-danger',
            'data-bs-toggle' => 'modal',
            'data-bs-target' => '#removeVirtualAssistantModalForm'
        ]) ?>

    </p>

    <?= DetailView::widget([
        'model' => $model,
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
            'name',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function($data) {
                    return $data->getStatusName();
                },
                'filter' => VirtualAssistant::getStatusesArray(),
            ],
            [
                'attribute' => 'author',
                'value' => $model->user->username,
            ],
            'description',
        ],
    ]) ?>


    <p>
        <?= Html::a('<i class="fa-solid fa-plus"></i> ' .
            Yii::t('app', 'VIRTUAL_ASSISTANT_PAGE_CREATE_VIRTUAL_ASSISTANT_MODEL'), ['create-vam', 'id' => $model->id],
            ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
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
                'filter' => Yii::$app->user->isGuest ? '' : VirtualAssistantModel::getTypesArray(),
                'visible' => !Yii::$app->user->isGuest,
                'filterInputOptions' => ['class' => 'form-select']
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['class' => 'action-column'],
                'template' => '{view-vam} {update-vam} {delete-vam}',
                'buttons' => [
                    'view-vam' => function ($url, $model_vam, $key) {
                        return Html::a('<i class="fa-solid fa-eye"></i>',
                            ['view-vam','id' => $model_vam->virtual_assistant_id, 'id_vam' => $model_vam->id],
                            [
                                'title' => Yii::t('app', 'BUTTON_VIEW_VAM'),
                                'aria-label' => Yii::t('app', 'BUTTON_VIEW_VAM')
                            ]
                        );
                    },
                    'update-vam' => function ($url, $model_vam, $key) {
                        return Html::a('<i class="fa-solid fa-pencil"></i>',
                            ['update-vam','id' => $model_vam->virtual_assistant_id, 'id_vam' => $model_vam->id],
                            [
                                'title' => Yii::t('app', 'BUTTON_UPDATE_VAM'),
                                'aria-label' => Yii::t('app', 'BUTTON_UPDATE_VAM')
                            ]
                        );
                    },
                    'delete-vam' => function ($url, $model_vam, $key) {
                        return Html::a('<i class="fa-solid fa-trash"></i>',
                            ['delete-vam','id' => $model_vam->virtual_assistant_id, 'id_vam' => $model_vam->id],
                            [
                                'title' => Yii::t('app', 'BUTTON_DELETE_VAM'),
                                'aria-label' => Yii::t('app', 'BUTTON_DELETE_VAM')
                            ]
                        );
                    },
                ]
            ]
        ],
    ]); ?>

</div>
