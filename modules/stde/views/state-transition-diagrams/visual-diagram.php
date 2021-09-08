<?php

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Diagram */

use yii\helpers\Html;
use app\modules\main\models\Lang;

$this->title = Yii::t('app', 'DIAGRAMS_PAGE_VISUAL_DIAGRAM') . ' - ' . $model->name;

$this->params['menu_add'] = [
];

$this->params['menu_diagram'] = [
];
?>

<div class="state-transition-diagram-visual-diagram">
    <h1><?= Html::encode($this->title) ?></h1>
</div>

<div id="visual-diagram" class="visual-diagram col-md-12">
</div>