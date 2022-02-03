<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\main\models\DiagramSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $array_template app\modules\main\controllers\DefaultController */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ButtonDropdown;
use app\modules\main\models\Diagram;
use app\modules\eete\models\TreeDiagram;

$this->title = Yii::t('app', 'DIAGRAMS_PAGE_MY_DIAGRAMS');

$this->params['breadcrumbs'][] = $this->title;
?>

<?php $this->registerCssFile('/css/index.css', ['position'=>yii\web\View::POS_HEAD]); ?>

<div class="my-diagrams">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (!Yii::$app->user->isGuest): ?>
        <div class="buttons">
            <?= Html::a('<span class="glyphicon glyphicon-edit"></span> ' .
                Yii::t('app', 'DIAGRAMS_PAGE_CREATE_DIAGRAM'),
                ['create'], ['class' => 'btn btn-success']) ?>
            <?= ButtonDropdown::widget([
                'label' => '<span class="glyphicon glyphicon-share"></span> ' .
                    Yii::t('app', 'DIAGRAMS_PAGE_CREATE_FROM_TEMPLATE'),
                'encodeLabel' => false,
                'options' => [
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
            ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function($data) {
                    return $data->getStatusName();
                },
                'filter' => Yii::$app->user->isGuest ? '' : Diagram::getStatusesArray(),
                'visible' => !Yii::$app->user->isGuest,
            ],
            [
                'attribute' => 'correctness',
                'format' => 'raw',
                'value' => function($data) {
                    return $data->getCorrectnessName();
                },
                'filter' => Yii::$app->user->isGuest ? '': Diagram::getCorrectnessArray(),
                'visible' => !Yii::$app->user->isGuest,
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
                        return Html::a('<span class="glyphicon glyphicon-blackboard"></span>',
                            $url,
                            [
                                'title' => Yii::t('app', 'BUTTON_OPEN_DIAGRAM'),
                                'aria-label' => Yii::t('app', 'BUTTON_OPEN_DIAGRAM')
                            ]
                        );
                    },
                    'import' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-import"></span>',
                            ['import', 'id' => $model->id],
                            [
                                'title' => Yii::t('app', 'BUTTON_IMPORT'),
                                'aria-label' => Yii::t('app', 'BUTTON_IMPORT')
                            ]
                        );
                    },
                    'export' => function ($url, $model, $key) {
                        $url = '#';
                        $tree_diagram = TreeDiagram::find()->where(['diagram' => $model->id])->one();
                        if (!empty($tree_diagram))
                            $url = ['/eete/tree-diagrams/visual-diagram/', 'id' => $tree_diagram->id];
                        if ($model->type == Diagram::STATE_TRANSITION_DIAGRAM_TYPE)
                            $url = ['/stde/state-transition-diagrams/visual-diagram/', 'id' => $model->id];
                        return Html::a('<span class="glyphicon glyphicon-export"></span>',
                            $url,
                            [
                                'data' => ['method' => 'post'],
                                'title' => Yii::t('app', 'BUTTON_EXPORT'),
                                'aria-label' => Yii::t('app', 'BUTTON_EXPORT')
                            ]
                        );
                    },
                    'upload-ontology' => function ($url, $model, $key) {
                        return $model->type == Diagram::STATE_TRANSITION_DIAGRAM_TYPE ? Html::a(
                            '<span class="glyphicon glyphicon-download-alt"></span>',
                            ['upload-ontology', 'id' => $model->id],
                            [
                                'title' => Yii::t('app', 'BUTTON_UPLOAD_ONTOLOGY'),
                                'aria-label' => Yii::t('app', 'BUTTON_UPLOAD_ONTOLOGY')
                            ]
                        ) : false;
                    },
                ],
            ],
        ],
    ]); ?>

</div>