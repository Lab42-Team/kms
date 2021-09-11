<?php

namespace app\modules\stde\controllers;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\modules\main\models\Diagram;
use app\modules\stde\models\State;
use app\modules\stde\models\Transition;

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


        $states_model_all = State::find()->where(['diagram' => $id])->all();



        $transitions_all = Transition::find()->all();
        $transitions_model_all = array();//массив пустых связей
        foreach ($transitions_all as $t){
            foreach ($states_model_all as $s){
                if ($t->state_from == $s->id){
                    array_push($transitions_model_all, $t);
                }
            }
        }





        return $this->render('visual-diagram', [
            'model' => $this->findModel($id),
            'states_model_all' => $states_model_all,
            'transitions_model_all' => $transitions_model_all,
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