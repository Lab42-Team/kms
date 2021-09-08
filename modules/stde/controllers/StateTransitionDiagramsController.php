<?php

namespace app\modules\stde\controllers;

use yii\web\Controller;

/**
 * Default controller for the `stde` module
 */
class StateTransitionDiagramsController extends Controller
{
    public $layout = '@app/modules/main/views/layouts/main';

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}