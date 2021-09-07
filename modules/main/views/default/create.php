<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Diagram */

$this->title = Yii::t('app', 'DIAGRAMS_PAGE_CREATE_DIAGRAM');

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'DIAGRAMS_PAGE_DIAGRAMS'),
    'url' => ['diagrams']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="create-diagram">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>