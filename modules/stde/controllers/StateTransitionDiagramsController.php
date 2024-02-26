<?php

namespace app\modules\stde\controllers;

use Yii;
use yii\web\Response;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\bootstrap5\ActiveForm;
use app\modules\main\models\Diagram;
use app\modules\stde\models\State;
use app\modules\stde\models\StateProperty;
use app\modules\stde\models\Transition;
use app\modules\stde\models\TransitionProperty;
use app\modules\stde\models\StartToEnd;
use app\modules\stde\models\StateConnection;
use app\components\StateTransitionXMLGenerator;
use app\components\DecisionTableGeneratorIDSS;

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

        $state_model = new State();
        $state_property_model = new StateProperty();
        $transition_model = new Transition();
        $transition_property_model = new TransitionProperty();

        $states_model_all = State::find()->where(['diagram' => $id])->all();

        $states_property_all = StateProperty::find()->all();
        $states_property_model_all = array();//массив связей
        foreach ($states_property_all as $sp){
            foreach ($states_model_all as $s){
                if ($sp->state == $s->id){
                    array_push($states_property_model_all, $sp);
                }
            }
        }

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

        //экспорт диаграммы
        if (Yii::$app->request->isPost) {
            if (Yii::$app->request->post('value', null) == 'xml'){
                $code_generator = new StateTransitionXMLGenerator();
                $code_generator->generateSTDXMLCode($id);
            }
            if (Yii::$app->request->post('value', null) == 'csv'){
                $code_generator = new DecisionTableGeneratorIDSS();
                $code_generator->generate($id);
            }
        }

        //все связи между StartToEnd и State
        $state_connections_all = StateConnection::find()->all();

        //начало диаграммы
        $start_model = StartToEnd::find()->where(['diagram' => $id, 'type' => StartToEnd::START_TYPE])->one();
        //связи между началом StartToEnd и State диаграммы
        $states_connection_start_model_all = array();//массив связей
        if ($start_model != null){
            foreach ($state_connections_all as $sc){
                if ($sc->start_to_end == $start_model->id) {
                    array_push($states_connection_start_model_all, $sc);
                }
            }
        }

        //завершение диаграммы
        $end_model = StartToEnd::find()->where(['diagram' => $id, 'type' => StartToEnd::END_TYPE])->one();
        //связи между завершением StartToEnd и State диаграммы
        $states_connection_end_model_all = array();//массив связей
        if ($end_model != null){
            foreach ($state_connections_all as $sc){
                if ($sc->start_to_end == $end_model->id) {
                    array_push($states_connection_end_model_all, $sc);
                }
            }
        }

        $start_count = StartToEnd::find()->where(['diagram' => $id, 'type' => StartToEnd::START_TYPE])->count();//количество начал
        $end_count = StartToEnd::find()->where(['diagram' => $id, 'type' => StartToEnd::END_TYPE])->count();//количество завершений

        return $this->render('visual-diagram', [
            'model' => $this->findModel($id),
            'state_model' => $state_model,
            'state_property_model' => $state_property_model,
            'transition_model' => $transition_model,
            'transition_property_model' => $transition_property_model,
            'states_model_all' => $states_model_all,
            'states_property_model_all' => $states_property_model_all,
            'transitions_model_all' => $transitions_model_all,
            'transitions_property_model_all' => $transitions_property_model_all,
            'start_model' => $start_model,
            'end_model' => $end_model,
            'states_connection_start_model_all' => $states_connection_start_model_all,
            'states_connection_end_model_all' => $states_connection_end_model_all,
            'start_count' => $start_count,
            'end_count' => $end_count,
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
     * Добавление нового состояния.
     *
     * @param $id - id дерева событий
     * @return bool|\yii\console\Response|Response
     */
    public function actionAddState($id)
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;
            // Формирование модели уровня
            $model = new State();
            // Задание id диаграммы
            $model->diagram = $id;

            // Определение полей модели уровня и валидация формы
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                // Условие проверки является ли состояние инициирующим
                $i = State::find()->where(['diagram' => $id, 'type' => State::INITIAL_STATE_TYPE])->count();
                // Если инициирующие состояние есть
                if ($i > '0') {
                    // Тип присваивается константа "COMMON_STATE_TYPE" как обычное состояние
                    $model->type = State::COMMON_STATE_TYPE;
                } else {
                    // Тип присваивается константа "INITIAL_STATE_TYPE" как начальное (инициирующее) состояния
                    $model->type = State::INITIAL_STATE_TYPE;
                }

                // Успешный ввод данных
                $data["success"] = true;
                // Добавление нового состояния в БД
                $model->save();
                // Формирование данных о новом состоянии
                $data["id"] = $model->id;
                $data["name"] = $model->name;
                $data["description"] = $model->description;
                $data["indent_x"] = $model->indent_x;
                $data["indent_y"] = $model->indent_y;
            } else
                $data = ActiveForm::validate($model);
            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    /**
     * Изменение состояния.
     */
    public function actionEditState()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            $model = State::find()->where(['id' => Yii::$app->request->post('state_id_on_click')])->one();

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                // Успешный ввод данных
                $data["success"] = true;
                // Формирование данных об измененном событии
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


    /**
     * Удаление состояния.
     */
    public function actionDeleteState()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            $state_id_on_click = Yii::$app->request->post('state_id_on_click');

            $state = State::find()->where(['id' => $state_id_on_click])->one();
            $state -> delete();

            $data["success"] = true;

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    /**
     * Копирование состояния.
     */
    public function actionCopyState()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            $state = State::find()->where(['id' => Yii::$app->request->post('state_id_on_click')])->one();

            // Формирование модели состояния
            $model = new State();
            // Задание id диаграммы
            $model->diagram = $state->diagram;
            // Присваивает новому состоянию местопоожение правее копируемого
            $model->indent_x = $state->indent_x + 160;
            $model->indent_y = $state->indent_y;

            // Определение полей модели уровня и валидация формы
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                // Тип присваивается константа "COMMON_STATE_TYPE" как обычное состояние
                $model->type = State::COMMON_STATE_TYPE;
                // Добавление нового состояния в БД
                $model->save();

                $i = 0;
                //копирование свойств состояний
                $state_property = StateProperty::find()->where(['state' => $state->id])->all();
                foreach ($state_property as $sp){
                    $new_state_property = new StateProperty();
                    $new_state_property->name = $sp->name;
                    $new_state_property->description = $sp->description;
                    $new_state_property->operator = $sp->operator;
                    $new_state_property->value = $sp->value;
                    $new_state_property->state = $model->id;
                    $new_state_property->save();

                    $data["state_property_id_$i"] = $new_state_property->id;
                    $data["state_property_name_$i"] = $new_state_property->name;
                    $data["state_property_description_$i"] = $new_state_property->description;
                    $data["state_property_operator_$i"] = $new_state_property->operator;
                    $data["state_property_operator_name_$i"] = $new_state_property->getOperatorName();
                    $data["state_property_value_$i"] = $new_state_property->value;

                    $i = $i + 1;
                }

                // Успешный ввод данных
                $data["success"] = true;

                // Формирование данных о новом состоянии
                $data["id"] = $model->id;

                $data["name"] = $model->name;
                $data["description"] = $model->description;
                $data["indent_x"] = $model->indent_x;
                $data["indent_y"] = $model->indent_y;
                $data["i"] = $i;
            } else
                $data = ActiveForm::validate($model);
            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    /**
     * Добавление нового свойства состояния.
     *
     * @param $id - id дерева событий
     * @return bool|\yii\console\Response|Response
     */
    public function actionAddStateProperty()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;
            // Формирование модели свойства состояния
            $model = new StateProperty();

            $model->state = Yii::$app->request->post('state_id_on_click');

            //поиск количества свойст у выбранного состояния
            $state_property_count = StateProperty::find()->where(['state' => Yii::$app->request->post('state_id_on_click')])->count();
            $data["state_property_count"] = $state_property_count;

            // Определение полей модели уровня и валидация формы
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                // Успешный ввод данных
                $data["success"] = true;
                // Добавление нового уровня в БД
                $model->save();
                // Формирование данных о новом уровне
                $data["id"] = $model->id;
                $data["name"] = $model->name;
                $data["description"] = $model->description;
                $data["operator"] = $model->operator;
                $data["operator_name"] = $model->getOperatorName();
                $data["value"] = $model->value;
                $data["state"] = $model->state;

            } else
                $data = ActiveForm::validate($model);
            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    /**
     * Изменение свойства состояния.
     */
    public function actionEditStateProperty()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            $model = StateProperty::find()->where(['id' => Yii::$app->request->post('state_property_id_on_click')])->one();

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                // Успешный ввод данных
                $data["success"] = true;

                $data["id"] = $model->id;
                $data["name"] = $model->name;
                $data["description"] = $model->description;
                $data["operator_name"] = $model->getOperatorName();
                $data["operator"] = $model->operator;
                $data["value"] = $model->value;

            } else
                $data = ActiveForm::validate($model);

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    /**
     * Удаление свойства состояния.
     */
    public function actionDeleteStateProperty()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            $model = StateProperty::find()->where(['id' => Yii::$app->request->post('state_property_id_on_click')])->one();
            $state_id = $model->state;
            $model -> delete();

            //поиск количества свойст у выбранного состояния
            $state_property_count = StateProperty::find()->where(['state' => $state_id])->count();
            $data["state_property_count"] = $state_property_count;
            $data["state_id"] = $state_id;

            $data["success"] = true;

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    /**
     * Добавление нового перехода.
     *
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
                $data["state_from"] = $model->state_from;
                $data["state_to"] = $model->state_to;

                // ----------Формирование модели условия
                $transition_property = new TransitionProperty();
                $transition_property->name = $model->name_property;
                $transition_property->description = $model->description_property;
                $transition_property->operator = $model->operator_property;
                $transition_property->value = $model->value_property;
                $transition_property->transition = $model->id;
                $transition_property->save();

                // --------------Формирование данных о новом переходе
                $data["id_property"] = $transition_property->id;
                $data["name_property"] = $transition_property->name;
                $data["description_property"] = $transition_property->description;
                $data["operator_property"] = $transition_property->getOperatorName();
                $data["value_property"] = $transition_property->value;

            } else
                $data = ActiveForm::validate($model);
            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    /**
     * Изменение перехода.
     */
    public function actionEditTransition()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            $model = Transition::find()->where(['id' => Yii::$app->request->post('transition_id_on_click')])->one();

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                // Успешный ввод данных
                $data["success"] = true;
                // Формирование данных об измененном событии
                $data["id"] = $model->id;
                $data["name"] = $model->name;
                $data["description"] = $model->description;
                $data["state_from"] = $model->state_from;
                $data["state_to"] = $model->state_to;
            } else
                $data = ActiveForm::validate($model);

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    /**
     * Удаление перехода.
     */
    public function actionDeleteTransition()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            $model = Transition::find()->where(['id' => Yii::$app->request->post('transition_id_on_click')])->one();
            $data["state_from"] = $model->state_from;
            $data["state_to"] = $model->state_to;
            $model -> delete();

            $data["success"] = true;

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    /**
     * Добавление нового условия.
     *
     */
    public function actionAddTransitionProperty()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;
            // Формирование модели уровня
            $model = new TransitionProperty();

            $model->transition = Yii::$app->request->post('transition_id_on_click');

            //поиск количества условий
            $transition_property_count = TransitionProperty::find()->where(['transition' => Yii::$app->request->post('transition_id_on_click')])->count();
            $data["transition_property_count"] = $transition_property_count;

            // Определение полей модели уровня и валидация формы
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                // Успешный ввод данных
                $data["success"] = true;
                // Добавление нового уровня в БД
                $model->save();
                // Формирование данных о новом уровне
                $data["id"] = $model->id;
                $data["name"] = $model->name;
                $data["description"] = $model->description;
                $data["operator"] = $model->operator;
                $data["operator_name"] = $model->getOperatorName();
                $data["value"] = $model->value;
                $data["transition"] = $model->transition;

            } else
                $data = ActiveForm::validate($model);
            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    /**
     * Изменение условия.
     */
    public function actionEditTransitionProperty()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            $model = TransitionProperty::find()->where(['id' => Yii::$app->request->post('transition_property_id_on_click')])->one();

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                // Успешный ввод данных
                $data["success"] = true;

                $data["id"] = $model->id;
                $data["name"] = $model->name;
                $data["description"] = $model->description;
                $data["operator_name"] = $model->getOperatorName();
                $data["operator"] = $model->operator;
                $data["value"] = $model->value;

            } else
                $data = ActiveForm::validate($model);

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    /**
     * Удаление условия.
     */
    public function actionDeleteTransitionProperty()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            $model = TransitionProperty::find()->where(['id' => Yii::$app->request->post('transition_property_id_on_click')])->one();
            $transition_id = $model->transition;
            $model -> delete();

            //поиск количества свойст у выбранного состояния
            $transition_property_count = TransitionProperty::find()->where(['transition' => $transition_id])->count();
            $data["transition_property_count"] = $transition_property_count;
            $data["transition_id"] = $transition_id;

            $data["success"] = true;

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    /**
     * Сохранение отступов.
     *
     */
    public function actionSaveIndent()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            $state = State::find()->where(['id' => Yii::$app->request->post('state_id')])->one();
            $state->indent_x = Yii::$app->request->post('indent_x');
            $state->indent_y = Yii::$app->request->post('indent_y');
            $state->updateAttributes(['indent_x']);
            $state->updateAttributes(['indent_y']);

            $data["indent_x"] = $state->indent_x;
            $data["indent_y"] = $state->indent_y;
            $data["success"] = true;

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    /**
     * Добавление начала.
     *
     * @param $id - id дерева перехода состояний
     * @return bool|\yii\console\Response|Response
     */
    public function actionAddStart($id)
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;
            // Формирование модели уровня
            $model = new StartToEnd();
            // Задание id диаграммы
            $model->diagram = $id;
            $model->type = StartToEnd::START_TYPE;
            // Успешный ввод данных
            $data["success"] = true;
            // Добавление нового состояния в БД
            $model->save();
            $data["id"] = $model->id;

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    /**
     * Удаление начала.
     *
     * @param $id - id дерева перехода состояний
     * @return bool|\yii\console\Response|Response
     */
    public function actionDeleteStart()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            $start = StartToEnd::find()->where(['id' => Yii::$app->request->post('id_start')])->one();
            $start_id = $start->id;
            $start -> delete();

            // Успешный ввод данных
            $data["success"] = true;
            $data["id"] = $start_id;

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    /**
     * Добавление завешения.
     *
     * @param $id - id дерева перехода состояний
     * @return bool|\yii\console\Response|Response
     */
    public function actionAddEnd($id)
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;
            // Формирование модели уровня
            $model = new StartToEnd();
            // Задание id диаграммы
            $model->diagram = $id;
            $model->type = StartToEnd::END_TYPE;
            // Успешный ввод данных
            $data["success"] = true;
            // Добавление нового состояния в БД
            $model->save();
            $data["id"] = $model->id;

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    /**
     * Удаление завершения.
     *
     * @param $id - id дерева перехода состояний
     * @return bool|\yii\console\Response|Response
     */
    public function actionDeleteEnd()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            $end = StartToEnd::find()->where(['id' => Yii::$app->request->post('id_end')])->one();
            $end_id = $end->id;
            $end -> delete();

            // Успешный ввод данных
            $data["success"] = true;
            $data["id"] = $end_id;

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    /**
     * Добавление связи с началом
     *
     * @return bool|\yii\console\Response|Response
     */
    public function actionStartConnection()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            // Формирование модели уровня
            $model = new StateConnection();
            $model->start_to_end = Yii::$app->request->post('id_start');
            $model->state = Yii::$app->request->post('id_state');

            // Успешный ввод данных
            $data["success"] = true;
            // Добавление нового состояния в БД
            $model->save();
            $data["id"] = $model->id;
            $data["id_start"] = $model->start_to_end;
            $data["id_state"] = $model->state;

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    /**
     * Добавление связи с завешением
     *
     * @return bool|\yii\console\Response|Response
     */
    public function actionEndConnection()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            // Формирование модели уровня
            $model = new StateConnection();
            $model->start_to_end = Yii::$app->request->post('id_end');
            $model->state = Yii::$app->request->post('id_state');

            // Успешный ввод данных
            $data["success"] = true;
            // Добавление нового состояния в БД
            $model->save();
            $data["id"] = $model->id;
            $data["id_end"] = $model->start_to_end;
            $data["id_state"] = $model->state;

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    /**
     * Удаление связи начала или завершения с состоянием
     *
     * @return bool|\yii\console\Response|Response
     */
    public function actionDelStateConnection()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            $id_start_to_end = Yii::$app->request->post('id_start_to_end');
            $id_state = Yii::$app->request->post('id_state');

            $state_connection = StateConnection::find()->where(['start_to_end' => $id_start_to_end, 'state' => $id_state])->one();
            $state_connection_id = $state_connection->id;
            $state_connection -> delete();

            // Успешный ввод данных
            $data["success"] = true;
            // Добавление нового состояния в БД
            $data["id"] = $state_connection_id;

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }


    /**
     * Сохранение отступов начала и завершения.
     *
     */
    public function actionSaveIndentStartOrEnd()
    {
        //Ajax-запрос
        if (Yii::$app->request->isAjax) {
            // Определение массива возвращаемых данных
            $data = array();
            // Установка формата JSON для возвращаемых данных
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;

            $start_to_end = StartToEnd::find()->where(['id' => Yii::$app->request->post('start_or_end_id')])->one();
            $start_to_end->indent_x = Yii::$app->request->post('indent_x');
            $start_to_end->indent_y = Yii::$app->request->post('indent_y');
            $start_to_end->updateAttributes(['indent_x']);
            $start_to_end->updateAttributes(['indent_y']);

            $data["indent_x"] = $start_to_end->indent_x;
            $data["indent_y"] = $start_to_end->indent_y;
            $data["success"] = true;

            // Возвращение данных
            $response->data = $data;
            return $response;
        }
        return false;
    }
}