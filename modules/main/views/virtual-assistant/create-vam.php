<?php

use app\modules\main\models\User;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\main\models\VirtualAssistantModel $model_vam */

$this->title = Yii::t('app', 'VIRTUAL_ASSISTANT_PAGE_CREATE_VIRTUAL_ASSISTANT_MODEL');

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'VIRTUAL_ASSISTANT_PAGE_VIRTUAL_ASSISTANTS'),
    'url' => ['list']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'VIRTUAL_ASSISTANT_PAGE_VIRTUAL_ASSISTANT') .
    ' - ' . $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="virtual-assistant-model-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form_vam', [
        'model_vam' => $model_vam,
    ]) ?>

</div>
