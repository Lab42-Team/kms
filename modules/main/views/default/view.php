<?php

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Diagram */

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\modules\main\models\User;
use app\modules\main\models\Diagram;
use app\modules\eete\models\TreeDiagram;

$this->title = Yii::t('app', 'DIAGRAMS_PAGE_DIAGRAM') . ' - ' . $model->name;

$this->params['breadcrumbs'][] = Yii::$app->user->identity->role == User::ROLE_ADMINISTRATOR ?
    ['label' => Yii::t('app', 'DIAGRAMS_PAGE_DIAGRAMS'), 'url' => ['diagrams']] :
    ['label' => Yii::t('app', 'DIAGRAMS_PAGE_MY_DIAGRAMS'), 'url' => ['my-diagrams']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$url = '#';
$tree_diagram = TreeDiagram::find()->where(['diagram' => $model->id])->one();
if (!empty($tree_diagram))
    $url = ['/eete/tree-diagrams/visual-diagram/', 'id' => $tree_diagram->id];
if ($model->type == Diagram::STATE_TRANSITION_DIAGRAM_TYPE)
    $url = ['/stde/state-transition-diagrams/visual-diagram/', 'id' => $model->id];
?>

<?= $this->render('_modal_form_diagrams', ['model' => $model]); ?>

<div class="view-diagram">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-blackboard"></span> ' .
            Yii::t('app', 'BUTTON_OPEN_DIAGRAM'), $url, ['class' => 'btn btn-success']
        ) ?>
        <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> ' .
            Yii::t('app', 'BUTTON_UPDATE'),
            ['update', 'id' => $model->id], ['class' => 'btn btn-primary']
        ) ?>
        <?= Html::a('<span class="glyphicon glyphicon-import"></span> ' .
            Yii::t('app', 'BUTTON_IMPORT'), ['import', 'id' => $model->id], ['class' => 'btn btn-primary']
        ) ?>
        <?= Html::a('<span class="glyphicon glyphicon-export"></span> ' .
            Yii::t('app', 'BUTTON_EXPORT'), $url,
            ['data' => ['method' => 'post'], 'class' => 'btn btn-primary']
        ) ?>
        <?= $model->type == Diagram::STATE_TRANSITION_DIAGRAM_TYPE ?
            Html::a('<span class="glyphicon glyphicon-download-alt"></span> ' .
                Yii::t('app', 'BUTTON_UPLOAD_ONTOLOGY'),
                ['upload-ontology', 'id' => $model->id], ['class' => 'btn btn-primary']
            ) : false
        ?>
        <?= $model->type == Diagram::STATE_TRANSITION_DIAGRAM_TYPE ?
            Html::a('<span class="glyphicon glyphicon-download-alt"></span> ' .
                Yii::t('app', 'BUTTON_UPLOAD_CSV'),
                ['upload-csv', 'id' => $model->id], ['class' => 'btn btn-primary']
            ) : false
        ?>
        <?= Html::a('<span class="glyphicon glyphicon-trash"></span> ' .
            Yii::t('app', 'BUTTON_DELETE'), ['#'], [
            'class' => 'btn btn-danger',
            'data-toggle' => 'modal',
            'data-target' => '#removeDiagramModalForm'
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'created_at',
                'format' => ['date', 'dd.MM.Y HH:mm:ss']
            ],
            [
                'attribute' => 'updated_at',
                'format' => ['date', 'dd.MM.Y HH:mm:ss']
            ],
            [
                'attribute' => 'author',
                'value' => $model->user->username,
            ],
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
                'filter' => Diagram::getStatusesArray(),
            ],
            [
                'attribute' => Yii::t('app', 'TREE_DIAGRAM_MODEL_MODE'),
                'format' => 'raw',
                'value' => function($data) {
                    return $data->treeDiagram->getModesName();
                },
                'visible' => $visible,
            ],
            [
                'attribute' => 'correctness',
                'format' => 'raw',
                'value' => function($data) {
                    return $data->getCorrectnessName();
                },
                'filter' => Diagram::getCorrectnessArray(),
            ],
            'description',
        ],
    ]) ?>

</div>