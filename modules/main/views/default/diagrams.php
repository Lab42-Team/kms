<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\main\models\DiagramSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $array_template app\modules\main\controllers\DefaultController */

use yii\bootstrap5\Html;
use yii\grid\GridView;
//use yii\bootstrap5\ButtonDropdown;
use kartik\bs5dropdown\ButtonDropdown;
use app\modules\main\models\User;
use app\modules\main\models\Diagram;
use app\modules\eete\models\TreeDiagram;

$this->title = Yii::t('app', 'DIAGRAMS_PAGE_DIAGRAMS');

$this->params['breadcrumbs'][] = $this->title;
?>

<?php $this->registerCssFile('/css/index.css', ['position'=>yii\web\View::POS_HEAD]); ?>

<div class="diagrams">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (!Yii::$app->user->isGuest): ?>
        <div class="buttons">
            <?= Html::a('<i class="fa-solid fa-pen-to-square"></i> ' .
                Yii::t('app', 'DIAGRAMS_PAGE_CREATE_DIAGRAM'),
                ['create'], ['class' => 'btn btn-success']) ?>
            <?= ButtonDropdown::widget([
                'label' => '<i class="fa-solid fa-share-from-square"></i> ' .
                    Yii::t('app', 'DIAGRAMS_PAGE_CREATE_FROM_TEMPLATE'),
                'encodeLabel' => false,
                'buttonOptions' => [
                    'class' => 'btn btn-primary',
                ],
                'dropdown' => [
                    'items' => $array_template,
                ],
            ]); ?>
        </div>
    <?php endif; ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            [
                'attribute' => 'type',
                'format' => 'raw',
                'value' => function($data) {
                    return $data->getTypeName();
                },
                'filter' => Diagram::getTypesArray(),
                'filterInputOptions' => ['class' => 'form-select']
            ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function($data) {
                    return $data->getStatusName();
                },
                'filter' => Yii::$app->user->isGuest ? '' : Diagram::getStatusesArray(),
                'visible' => !Yii::$app->user->isGuest,
                'filterInputOptions' => ['class' => 'form-select']
            ],
            [
                'attribute' => 'correctness',
                'format' => 'raw',
                'value' => function($data) {
                    return $data->getCorrectnessName();
                },
                'filter' => Yii::$app->user->isGuest ? '': Diagram::getCorrectnessArray(),
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
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['class' => 'action-column'],
                'template' => Yii::$app->user->isGuest ? '{visual-diagram} {view}' :
                    '{visual-diagram} {view} {update} {delete} {import} {export} {upload-ontology}',
                'buttons' => [
                    'visual-diagram' => function ($url, $model, $key) {
                        $url = '#';
                        $tree_diagram = TreeDiagram::find()->where(['diagram' => $model->id])->one();
                        if (!empty($tree_diagram))
                            $url = ['/eete/tree-diagrams/visual-diagram/', 'id' => $tree_diagram->id];
                        if ($model->type == Diagram::STATE_TRANSITION_DIAGRAM_TYPE)
                            $url = ['/stde/state-transition-diagrams/visual-diagram/', 'id' => $model->id];
                        return Html::a('<i class="fa-solid fa-display"></i>',
                            $url,
                            [
                                'title' => Yii::t('app', 'BUTTON_OPEN_DIAGRAM'),
                                'aria-label' => Yii::t('app', 'BUTTON_OPEN_DIAGRAM')
                            ]
                        );
                    },
                    'import' => function ($url, $model, $key) {
                        return Html::a('<i class="fa-solid fa-file-import"></i>',
                            ['import', 'id' => $model->id],
                            [
                                'title' => Yii::t('app', 'BUTTON_IMPORT'),
                                'aria-label' => Yii::t('app', 'BUTTON_IMPORT')
                            ]
                        );
                    },
                    'export' => function ($url, $model, $key) {
                        $url = '#';
                        $data = [];
                        $tree_diagram = TreeDiagram::find()->where(['diagram' => $model->id])->one();
                        if (!empty($tree_diagram)){
                                $url = ['/eete/tree-diagrams/visual-diagram/', 'id' => $tree_diagram->id];
                                $data = [
                                    'data' => ['method' => 'post'],
                                    'title' => Yii::t('app', 'BUTTON_EXPORT'),
                                    'aria-label' => Yii::t('app', 'BUTTON_EXPORT')
                                ];
                            }
                        if ($model->type == Diagram::STATE_TRANSITION_DIAGRAM_TYPE){
                                $url = ['/stde/state-transition-diagrams/visual-diagram/', 'id' => $model->id];
                                $data = [
                                    'data' => [
                                            'method' => 'post',
                                            'params' => ['value' => 'xml'],
                                    ],
                                    'title' => Yii::t('app', 'BUTTON_EXPORT'),
                                    'aria-label' => Yii::t('app', 'BUTTON_EXPORT')
                                ];
                            }
                        return Html::a('<i class="fa-solid fa-file-export"></i>',
                            $url, $data
                        );
                    },
                    'upload-ontology' => function ($url, $model, $key) {
                        return $model->type == Diagram::STATE_TRANSITION_DIAGRAM_TYPE ? Html::a(
                            '<i class="fa-solid fa-download"></i>',
                            ['upload-ontology', 'id' => $model->id],
                            [
                                'title' => Yii::t('app', 'BUTTON_UPLOAD_ONTOLOGY'),
                                'aria-label' => Yii::t('app', 'BUTTON_UPLOAD_ONTOLOGY')
                            ]
                        ) : false;
                    },
                ]
            ]
        ]
    ]); ?>

</div>