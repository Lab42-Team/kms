<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\main\models\User;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel app\modules\main\models\UserSearch */

$this->title = Yii::t('app', 'USERS_PAGE_USERS');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-list">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-edit"></span> ' .
            Yii::t('app', 'USERS_PAGE_CREATE_USER'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'username',
            [
                'attribute'=>'role',
                'value' => function($data) {
                    return ($data->role !== null) ? $data->getRoleName() : null;
                },
                'filter' => User::getRoles(),
            ],
            [
                'attribute'=>'status',
                'value' => function($data) {
                    return ($data->status !== null) ? $data->getStatusName() : null;
                },
                'filter' => User::getStatuses(),
            ],
            [
                'attribute'=>'full_name',
                'value' => function($data) {
                    return ($data->full_name != '') ? $data->full_name : null;
                },
            ],
            [
                'attribute'=>'email',
                'value' => function($data) {
                    return ($data->email != '') ? $data->email : null;
                },
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>