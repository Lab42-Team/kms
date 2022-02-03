<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\User */

$this->title = Yii::t('app', 'USER_PAGE_UPDATE_PASSWORD');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'USER_PAGE_PROFILE'),
    'url' => ['profile', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'USER_PAGE_UPDATE_PASSWORD') . ': ' . $model->username;
?>

<div class="change-password">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form_change_password', [
        'model' => $model,
    ]) ?>

</div>