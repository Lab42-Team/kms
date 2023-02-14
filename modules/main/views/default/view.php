<?php

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Diagram */

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\modules\main\models\User;
use app\modules\main\models\Diagram;
use app\modules\eete\models\TreeDiagram;

$this->title = Yii::t('app', 'DIAGRAMS_PAGE_DIAGRAM') . ' - ' . $model->name;

$this->params['breadcrumbs'][] =
    Yii::$app->user->isGuest ?
        ['label' => Yii::t('app', 'DIAGRAMS_PAGE_DIAGRAMS'), 'url' => ['diagrams']] :
        (Yii::$app->user->identity->role == User::ROLE_ADMINISTRATOR ?
            ['label' => Yii::t('app', 'DIAGRAMS_PAGE_DIAGRAMS'), 'url' => ['diagrams']] :
            ['label' => Yii::t('app', 'DIAGRAMS_PAGE_MY_DIAGRAMS'), 'url' => ['my-diagrams']]);
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
        <?= Html::a('<i class="fa-solid fa-display"></i> ' .
            Yii::t('app', 'BUTTON_OPEN_DIAGRAM'), $url, ['class' => 'btn btn-success']
        ) ?>
        <?= Html::a('<i class="fa-solid fa-pencil"></i> ' .
            Yii::t('app', 'BUTTON_UPDATE'),
            ['update', 'id' => $model->id], ['class' => 'btn btn-primary']
        ) ?>
        <?= Html::a('<i class="fa-solid fa-file-import"></i> ' .
            Yii::t('app', 'BUTTON_IMPORT'), ['import', 'id' => $model->id], ['class' => 'btn btn-primary']
        ) ?>
        <?= $model->type == Diagram::EVENT_TREE_TYPE ?
            Html::a('<i class="fa-solid fa-file-export"></i> ' .
                Yii::t('app', 'BUTTON_EXPORT'), $url,
                ['data' => ['method' => 'post'], 'class' => 'btn btn-primary']
            ) : false
        ?>
        <?= $model->type == Diagram::STATE_TRANSITION_DIAGRAM_TYPE ?
            Html::a('<i class="fa-solid fa-file-export"></i> ' .
                Yii::t('app', 'BUTTON_EXPORT'), $url,
                ['data' => ['method' => 'post', 'params' => ['value' => 'xml']], 'class' => 'btn btn-primary']
            ) : false
        ?>
        <?= $model->type == Diagram::STATE_TRANSITION_DIAGRAM_TYPE ?
            Html::a('<i class="fa-solid fa-download"></i> ' .
                Yii::t('app', 'BUTTON_UPLOAD_ONTOLOGY'),
                ['upload-ontology', 'id' => $model->id], ['class' => 'btn btn-primary']
            ) : false
        ?>
        <?= $model->type == Diagram::STATE_TRANSITION_DIAGRAM_TYPE ?
            Html::a('<i class="fa-solid fa-file-arrow-down"></i> ' .
                Yii::t('app', 'BUTTON_DECISION_TABLE'),
                ['upload-csv', 'id' => $model->id], ['class' => 'btn btn-primary']
            ) : false
        ?>
        <?= Html::a('<i class="fa-solid fa-trash"></i> ' .
            Yii::t('app', 'BUTTON_DELETE'), ['#'], [
            'class' => 'btn btn-danger',
            'data-bs-toggle' => 'modal',
            'data-bs-target' => '#removeDiagramModalForm'
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