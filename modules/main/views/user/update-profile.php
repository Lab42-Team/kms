<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\User */

$this->title = Yii::t('app', 'USER_PAGE_UPDATE_PROFILE');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'USER_PAGE_PROFILE'),
    'url' => ['profile', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'USER_PAGE_UPDATE_PROFILE') . ': ' . $model->username;
?>

<div class="update-profile">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form_update_profile', [
        'model' => $model,
    ]) ?>

</div>