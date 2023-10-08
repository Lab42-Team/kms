<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\main\models\VirtualAssistant $model */

$this->title = Yii::t('app', 'VIRTUAL_ASSISTANT_PAGE_UPDATE_VIRTUAL_ASSISTANT') . ': ' .$model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'VIRTUAL_ASSISTANT_PAGE_VIRTUAL_ASSISTANTS'),
    'url' => ['list']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'VIRTUAL_ASSISTANT_PAGE_VIRTUAL_ASSISTANT') .
    ' - ' . $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="virtual-assistant-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
