<?php

namespace app\modules\stde\controllers;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\modules\main\models\Diagram;

/**
 * StateTransitionDiagramsController implements the CRUD actions for State Transition Diagram model.
 */
class StateTransitionDiagramsController extends Controller
{
    public $layout = '@app/modules/main/views/layouts/main';

    /**
     * Страница визуального редактора диаграмм переходов состояний.
     *
     * @param $id - id диаграммы перехода состояний
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionVisualDiagram($id)
    {
        return $this->render('visual-diagram', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the Diagram model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param $id
     * @return Diagram|null the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Diagram::findOne($id)) !== null)
            return $model;

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}