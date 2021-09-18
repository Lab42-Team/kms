<?php

namespace app\modules\stde\controllers;

use Yii;
use yii\web\Response;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\bootstrap\ActiveForm;
use app\modules\main\models\Diagram;
use app\modules\stde\models\State;
use app\modules\stde\models\Transition;
use app\modules\stde\models\TransitionProperty;

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

        $transition_model = new Transition();

        $states_model_all = State::find()->where(['diagram' => $id])->all();

        $transitions_all = Transition::find()->all();
        $transitions_model_all = array();//массив связей
        foreach ($transitions_all as $t){
            foreach ($states_model_all as $s){
                if ($t->state_from == $s->id){
                    array_push($transitions_model_all, $t);
                }
            }
        }

        $transitions_property_all = TransitionProperty::find()->all();
        $transitions_property_model_all = array();//массив условий
        foreach ($transitions_property_all as $p){
            foreach ($transitions_model_all as $t){
                if ($p->transition == $t->id){
                    array_push($transitions_property_model_all, $p);
                }
            }
        }


        return $this->render('visual-diagram', [
            'model' => $this->findModel($id),
            'transition_model' => $transition_model,
            'states_model_all' => $states_model_all,
            'transitions_model_all' => $transitions_model_all,
            'transitions_property_model_all' => $transitions_property_model_all,
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


    /**
     * Добавление нового перехода.
     *
     * @param $id - id дерева событий
     * @return bool|\yii\console\Response|Response
     */
    public function actionAddTransition()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            // Формирование модели перехода
            $model = new Transition();

            $model->state_from = Yii::$app->request->post('id_state_from');
            $model->state_to = Yii::$app->request->post('id_state_to');

            // Определение полей модели перехода и валидация формы
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                // Успешный ввод данных
                $data["success"] = true;
                // Добавление нового перехода в БД
                $model->save();
                // Формирование данных о новом переходе
                $data["id"] = $model->id;
                $data["name"] = $model->name;
                $data["description"] = $model->description;
            } else
                $data = ActiveForm::validate($model);
            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }

}