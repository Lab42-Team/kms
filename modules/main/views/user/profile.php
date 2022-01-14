<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\User */

$this->title = Yii::t('app', 'USER_PAGE_PROFILE');
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="profile">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> ' .
            Yii::t('app', 'USER_PAGE_UPDATE_ACCOUNT_INFORMATION'),
            ['update-profile', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-refresh"></span> ' .
            Yii::t('app', 'USER_PAGE_UPDATE_PASSWORD'), ['change-password', 'id' => $model->id],
            ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'username',
            [
                'attribute'=>'full_name',
                'value' => $model->full_name != '' ? $model->full_name : null,
            ],
            [
                'attribute'=>'email',
                'value' => $model->full_name != '' ? $model->email : null,
            ],
            [
                'attribute'=>'role',
                'value' => $model->getRoleName()
            ],
            [
                'attribute'=>'status',
                'value' => $model->getStatusName()
            ],
            [
                'attribute' => 'created_at',
                'format' => ['date', 'dd.MM.Y HH:mm:ss']
            ],
            [
                'attribute' => 'updated_at',
                'format' => ['date', 'dd.MM.Y HH:mm:ss']
            ],
        ],
    ]) ?>

</div>