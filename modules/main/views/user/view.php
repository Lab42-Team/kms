<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\User */

$this->title = Yii::t('app', 'USERS_PAGE_USER') . ': ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'USERS_PAGE_USERS'), 'url' => ['list']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<?= $this->render('_modal_form_users', ['model' => $model]); ?>

<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> ' .
            Yii::t('app', 'BUTTON_UPDATE'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-trash"></span> ' .
            Yii::t('app', 'BUTTON_DELETE'), ['#'], [
            'class' => 'btn btn-danger',
            'data-toggle' => 'modal',
            'data-target' => '#removeUserModalForm'
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'created_at',
                'format' => ['date', 'dd.MM.Y HH:mm:ss']
            ],
            [
                'attribute' => 'updated_at',
                'format' => ['date', 'dd.MM.Y HH:mm:ss']
            ],
            'username',
            [
                'attribute' => 'auth_key',
                'value' => ($model->auth_key != '') ? $model->auth_key : null
            ],
            [
                'attribute' => 'role',
                'value' => ($model->role !== null) ? $model->getRoleName() : null,
            ],
            [
                'attribute' => 'status',
                'value' => ($model->status !== null) ? $model->getStatusName() : null,
            ],
            [
                'attribute' => 'full_name',
                'value' => ($model->full_name != '') ? $model->full_name : null
            ],
            [
                'attribute' => 'email',
                'value' => ($model->email != '') ? $model->email : null
            ],
        ],
    ]) ?>

</div>