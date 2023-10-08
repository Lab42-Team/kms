<?php

/* @var $this yii\web\View */

$this->title = 'Knowledge Modeling System';
?>

<div class="site-index">

    <div class="jumbotron text-center bg-transparent">
        <h1 class="display-4"><?php echo Yii::t('app', 'WELCOME_TO_KMS') ?></h1>
    </div>

    <div class="body-content">
        <p>
            <?php echo '<b>' . Yii::t('app', 'KMS_NAME') . '</b> ' .
                Yii::t('app', 'KMS_DEFINITION') ?><br /><br />
            <?php echo Yii::t('app', 'DIAGRAM_TYPES') ?><br />
            <ul>
                <li><i><?php echo Yii::t('app', 'FIRST_TYPE') ?></i></li>
                <li><i><?php echo Yii::t('app', 'SECOND_TYPE') ?></i></li>
            </ul>
            <hr />
            <?php echo '<b>' . Yii::t('app', 'EVENT_TREE_NAME') . '</b> ' .
                Yii::t('app', 'EVENT_TREE_DEFINITION') ?><br /><br />
            <?php echo Yii::t('app', 'ADVANCED_EVENT_TREE_DEFINITION') ?><br /><br />
            <?php echo Yii::t('app', 'EVENT_TREE_CREATION') .
                ' <b>' . Yii::t('app', 'EET_EDITOR'). '</b>' ?><br />
            <hr />
            <?php echo '<b>' . Yii::t('app', 'STATE_TRANSITION_DIAGRAM_NAME') . '</b> ' .
                Yii::t('app', 'STATE_TRANSITION_DIAGRAM_DEFINITION') ?><br /><br />
            <?php echo Yii::t('app', 'STATE_TRANSITION_DIAGRAM_CREATION') .
                ' <b>' . Yii::t('app', 'STD_EDITOR'). '</b>' ?><br />
            <hr />
            <?php echo Yii::t('app', 'YOU_CAN_SEE_THE_CREATED') .
                "<a href='diagrams'>" . Yii::t('app', 'DIAGRAMS') . "</a>." ?><br />
            <?php if (Yii::$app->user->isGuest == true): ?>
                <?php echo Yii::t('app', 'WARNING_FOR_DIAGRAM_CREATION') . '&nbsp;' .
                    Yii::t('app', 'TO_CREATE_DIAGRAM') . "<a href='/main/default/sing-in'>" .
                    Yii::t('app', 'SIGN_IN') . "</a>." ?>
            <?php else: ?>
                <?php echo Yii::t('app', 'YOU_CAN_CREATE') .
                    "<a href='create'>" . Yii::t('app', 'DIAGRAM') . "</a>." ?>
            <?php endif; ?>
        </p>
    </div>

</div>