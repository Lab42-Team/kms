<?php

use app\modules\main\models\Diagram;
use app\modules\main\models\User;
use app\modules\main\models\VirtualAssistant;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\modules\main\models\VirtualAssistantSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'VIRTUAL_ASSISTANT_PAGE_VIRTUAL_ASSISTANTS');

$this->params['breadcrumbs'][] = $this->title;
?>

<div class="virtual-assistant-list">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-edit"></span> ' .
            Yii::t('app', 'VIRTUAL_ASSISTANT_PAGE_CREATE_VIRTUAL_ASSISTANT'), ['create'],
            ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function($data) {
                    return $data->getStatusName();
                },
                'filter' => Yii::$app->user->isGuest ? '' : VirtualAssistant::getStatusesArray(),
                'visible' => !Yii::$app->user->isGuest,
                'filterInputOptions' => ['class' => 'form-select']
            ],
            [
                'attribute' => 'author',
                'format' => 'raw',
                'value' => function($data) {
                    return $data->user->username;
                },
                'filter' => User::getAllUsersArray(),
                'filterInputOptions' => ['class' => 'form-select']
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
                'attribute' => 'knowledge_base_model',
                'format' => 'raw',
                'value' => function($data) {
                    return $data->knowledgeBaseModel->name;
                },
                'filter' => Diagram::getAllStateTransitionDiagramArray(),
                'filterInputOptions' => ['class' => 'form-select']
            ],
            //[
            //    'class' => ActionColumn::className(),
            //    'urlCreator' => function ($action, VirtualAssistant $model, $key, $index, $column) {
            //        return Url::toRoute([$action, 'id' => $model->id]);
            //     }
            //],
            [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['class' => 'action-column'],
                'template' => '{view} {update} {delete} {generate}',
                'buttons' => [
                    'generate' => function ($url, $model, $key) {
                        return Html::a('<i class="fa-solid fa-table"></i>',
                            ['generate', 'id' => $model->id],
                            [
                                'title' => Yii::t('app', 'BUTTON_GENERATE'),
                                'aria-label' => Yii::t('app', 'BUTTON_GENERATE')
                            ]
                        );
                    },
                ]
            ]



        ],
    ]); ?>


</div>
