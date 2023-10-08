<?php

/* @var $this yii\web\View */
/* @var $content string */

use yii\helpers\Url;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use app\assets\AppAsset;
use app\components\widgets\WLang;
use app\modules\main\models\User;

AppAsset::register($this);
?>

<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="Content-Type" content="text/html">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://kit.fontawesome.com/57d69475c9.js" crossorigin="anonymous"></script>
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
    <?php $this->beginBody() ?>

    <div class="wrap">
        <?php NavBar::begin([
            'brandLabel' => Yii::$app->name,
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-expand-md navbar-dark bg-dark fixed-top',
            ],
        ]);

        echo Nav::widget([
            'options' => ['class' => 'navbar-nav  me-auto'],
            'encodeLabels' => false,
            'items' => array_filter([
                (!Yii::$app->user->isGuest and Yii::$app->user->identity->role == User::ROLE_USER) ? (
                    [
                        'label' => '<i class="fa-solid fa-list"></i> ' .
                            Yii::t('app', 'NAV_MY_DIAGRAMS'),
                        'url' => ['/main/default/my-diagrams']
                    ]
                ) : false,

                (Yii::$app->user->isGuest or Yii::$app->user->identity->role == User::ROLE_ADMINISTRATOR) ? (
                    [
                        'label' => '<i class="fa-solid fa-list"></i> ' .
                            Yii::t('app', 'NAV_DIAGRAMS'),
                        'url' => ['/main/default/diagrams']
                    ]
                ): false,


                (Yii::$app->user->isGuest or Yii::$app->user->identity->role == User::ROLE_ADMINISTRATOR) ? (
                [
                    'label' => '<i class="fa-solid fa-table"></i> ' .
                        Yii::t('app', 'NAV_VIRTUAL_ASSISTANTS'),
                    'url' => ['/main/virtual-assistant/list']
                ]
                ): false,


                !Yii::$app->user->isGuest ? (
                    // Условие проверки есть ли visual-diagram в URL
                    preg_match('/visual-diagram/', Url::current([], false)) == 1 ?
                    [
                        'label' => '<i class="fa-solid fa-plus"></i> ' .
                            Yii::t('app', 'NAV_ADD'),
                        'items' => $this->params['menu_add']
                    ] : false
                ) : false,

                !Yii::$app->user->isGuest ? (
                    // Условие проверки есть ли visual-diagram в URL
                    preg_match('/visual-diagram/', Url::current([], false)) == 1 ?
                    [
                        'label' => '<i class="fa-solid fa-display"></i> ' .
                            Yii::t('app', 'NAV_DIAGRAM'),
                        'items' => $this->params['menu_diagram']
                    ] : false
                ) : false,

                !Yii::$app->user->isGuest ? (
                    Yii::$app->user->identity->role == User::ROLE_ADMINISTRATOR ? [
                        'label' => '<i class="fa-solid fa-user-group"></i> ' .
                            Yii::t('app', 'NAV_USERS'),
                        'url' => ['/main/user/list']
                    ] : false
                    ) : ([
                        'label' => '<i class="fa-solid fa-envelope"></i> ' .
                            Yii::t('app', 'NAV_CONTACT_US'),
                        'url' => ['/main/default/contact']
                    ]
                ),
            ])
        ]);

        echo Nav::widget([
            'options' => ['class' => 'navbar-nav'],
            'encodeLabels' => false,
            'items' => array_filter([
                !Yii::$app->user->isGuest ? (
                    [
                        'label' => '<i class="fa-solid fa-house"></i> ' .
                            Yii::t('app', 'NAV_ACCOUNT'), 'url' => ['#'],
                        'items' => array_filter([
                            ['label' => '<i class="fa-solid fa-user"></i> ' .
                                Yii::t('app', 'NAV_PROFILE'),
                                'url' => ['/user/profile/' . Yii::$app->user->identity->getId()]],
                            ['label' => '<i class="fa-solid fa-envelope"></i> ' .
                                Yii::t('app', 'NAV_CONTACT_US'), 'url' => ['/main/default/contact']],
                            ['label' => '<i class="fa-solid fa-arrow-right-from-bracket"></i> ' .
                                Yii::t('app', 'NAV_SIGN_OUT'). ' (' .
                                Yii::$app->user->identity->username . ')',
                                'url' => ['/main/default/sing-out'], 'linkOptions' => ['data-method' => 'post']]
                        ])
                    ]
                ) : (
                    [
                        'label' => '<i class="fa-solid fa-arrow-right-to-bracket"></i> ' .
                            Yii::t('app', 'NAV_SIGN_IN'),
                        'url' => ['/main/default/sing-in']
                    ]
                ),
            ])
        ]);

        echo "<form class='navbar-form'>" . WLang::widget() . "</form>";

        NavBar::end(); ?>

        <div class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                'options' => [
                    'class' => 'px-4 bg-light rounded',
                ],
            ]) ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </div>

    <footer id="footer" class="mt-auto py-4 bg-light">
        <div class="container">
            <div class="row text-muted">
                <div class="col-md-6 text-center text-md-start">&copy; <?= date('Y') ?> <?= Yii::t('app', 'FOOTER_INSTITUTE') ?></div>
                <div class="col-md-6 text-center text-md-end"><?= Yii::t('app', 'FOOTER_POWERED_BY') .
                    ' <a href="https://github.com/Lab42-Team">Lab42-Team</a>' ?></div>
            </div>
        </div>
    </footer>

    <?php $this->endBody() ?>
    </body>
    </html>

<?php $this->endPage() ?>