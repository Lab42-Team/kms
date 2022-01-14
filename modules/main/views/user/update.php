<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\User */

$this->title = Yii::t('app', 'USERS_PAGE_UPDATE_USER_INFORMATION') . ': ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'USERS_PAGE_USERS'), 'url' => ['list']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'USERS_PAGE_USER') . ': ' . $model->username,
    'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'USERS_PAGE_UPDATE_USER_INFORMATION');
?>

<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form_update', [
        'model' => $model,
    ]) ?>

</div>