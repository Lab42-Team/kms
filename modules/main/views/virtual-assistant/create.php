<?php

use app\modules\main\models\User;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\main\models\VirtualAssistant $model */

$this->title = Yii::t('app', 'VIRTUAL_ASSISTANT_PAGE_CREATE_VIRTUAL_ASSISTANT');

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'VIRTUAL_ASSISTANT_PAGE_VIRTUAL_ASSISTANTS'),
    'url' => ['list']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="virtual-assistant-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
