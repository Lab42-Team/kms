<?php

use yii\helpers\Html;
use app\modules\main\models\User;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Diagram */

$this->title = Yii::t('app', 'DIAGRAMS_PAGE_UPDATE_DIAGRAM') . ': ' .$model->name;
$this->params['breadcrumbs'][] = Yii::$app->user->identity->role == User::ROLE_ADMINISTRATOR ?
    ['label' => Yii::t('app', 'DIAGRAMS_PAGE_DIAGRAMS'), 'url' => ['diagrams']] :
    ['label' => Yii::t('app', 'DIAGRAMS_PAGE_MY_DIAGRAMS'), 'url' => ['my-diagrams']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'DIAGRAMS_PAGE_DIAGRAM') . ' - ' . $model->name,
    'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="update-diagram">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>