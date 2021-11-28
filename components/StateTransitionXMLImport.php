<?php

namespace app\components;


use app\modules\main\models\Diagram;
use app\modules\stde\models\State;
use app\modules\stde\models\StateProperty;
use app\modules\stde\models\Transition;
use app\modules\stde\models\TransitionProperty;


class StateTransitionXMLImport
{
    public static $array_states;
    public static $j;

    /**
     * Очистка диаграммы.
     *
     * @param $id - идентификатор диаграммы
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public static function cleanDiagram($id)
    {
        $state_mas = State::find()->where(['diagram' => $id])->all();
        foreach ($state_mas as $elem)
            $elem->delete();
    }

    /**
     * Импорт XML-кода.
     *
     * @param $id - идентификатор диаграммы
     * @param $file - XML-файл дерева событий
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function importXMLCode($id, $file)
    {
        self::cleanDiagram($id);

        // Массив state (для создания связей)
        self::$array_states = array();
        self::$j = 0;

        // Создание Diagram
        $diagram_model = Diagram::find()->where(['id' => $id])->one();
        $diagram_model->description = (string)$file['description'];
        $diagram_model->save();

        // Создание State
        foreach($file->State as $state) {
            $state_model = new State();
            $state_model->name = (string)$state['name'];
            if ((string)$state['type'] == 'Initial state')
                $state_model->type = State::INITIAL_STATE_TYPE;
            if ((string)$state['type'] == 'State')
                $state_model->type = State::COMMON_STATE_TYPE;
            $state_model->description = (string)$state['description'];
            $state_model->diagram = $id;
            $state_model->save();

            // Таблица $array_states внесение значений, где:
            // 'state_template' значение id state из xml
            // 'state' значение нового id state из только что созданного
            self::$array_states[self::$j]['state_template'] = (integer)$state['id'];
            self::$array_states[self::$j]['state'] = $state_model->id;
            self::$j++;

            // Создание StateProperty
            foreach($state->children() as $state_property) {
                if ($state_property->getName() == 'StateProperty') {
                    $state_property_model = new StateProperty();
                    $state_property_model->name = (string)$state_property['name'];
                    $state_property_model->description = (string)$state_property['description'];
                    if ((string)$state_property['operator'] == '=')
                        $state_property_model->operator = StateProperty::EQUALLY_OPERATOR;
                    if ((string)$state_property['operator'] == '>')
                        $state_property_model->operator = StateProperty::MORE_OPERATOR;
                    if ((string)$state_property['operator'] == '<')
                        $state_property_model->operator = StateProperty::LESS_OPERATOR;
                    if ((string)$state_property['operator'] == '>=')
                        $state_property_model->operator = StateProperty::MORE_EQUAL_OPERATOR;
                    if ((string)$state_property['operator'] == '<=')
                        $state_property_model->operator = StateProperty::LESS_EQUAL_OPERATOR;
                    if ((string)$state_property['operator'] == '≠')
                        $state_property_model->operator = StateProperty::NOT_EQUAL_OPERATOR;
                    if ((string)$state_property['operator'] == '≈')
                        $state_property_model->operator = StateProperty::APPROXIMATELY_EQUAL_OPERATOR;
                    $state_property_model->value = (string)$state_property['value'];
                    $state_property_model->state = $state_model->id;
                    $state_property_model->save();
                }
            }
        }

        // Создание Transition
        foreach($file->Transition as $transition) {
            $transition_model = new Transition();
            $transition_model->name = (string)$transition['name'];
            $transition_model->description = (string)$transition['description'];
            for ($i = 0; $i < self::$j; $i++){
                if ((integer)$transition['state-from'] == self::$array_states[$i]['state_template'])
                    $transition_model->state_from = self::$array_states[$i]['state'];
            }
            for ($i = 0; $i < self::$j; $i++){
                if ((integer)$transition['state-to'] == self::$array_states[$i]['state_template'])
                    $transition_model->state_to = self::$array_states[$i]['state'];
            }
            $transition_model->name_property = "0";
            $transition_model->operator_property = 1;
            $transition_model->value_property = "0";
            $transition_model->save();

            // Создание TransitionProperty
            foreach($transition->children() as $transition_property) {
                if ($transition_property->getName() == 'TransitionProperty') {
                    $transition_property_model = new TransitionProperty();
                    $transition_property_model->name = (string)$transition_property['name'];
                    $transition_property_model->description = (string)$transition_property['description'];
                    if ((string)$transition_property['operator'] == '=')
                        $transition_property_model->operator = TransitionProperty::EQUALLY_OPERATOR;
                    if ((string)$transition_property['operator'] == '>')
                        $transition_property_model->operator = TransitionProperty::MORE_OPERATOR;
                    if ((string)$transition_property['operator'] == '<')
                        $transition_property_model->operator = TransitionProperty::LESS_OPERATOR;
                    if ((string)$transition_property['operator'] == '>=')
                        $transition_property_model->operator = TransitionProperty::MORE_EQUAL_OPERATOR;
                    if ((string)$transition_property['operator'] == '<=')
                        $transition_property_model->operator = TransitionProperty::LESS_EQUAL_OPERATOR;
                    if ((string)$transition_property['operator'] == '≠')
                        $transition_property_model->operator = TransitionProperty::NOT_EQUAL_OPERATOR;
                    if ((string)$transition_property['operator'] == '≈')
                        $transition_property_model->operator = TransitionProperty::APPROXIMATELY_EQUAL_OPERATOR;
                    $transition_property_model->value = (string)$transition_property['value'];
                    $transition_property_model->transition = $transition_model->id;
                    $transition_property_model->save();
                }
            }
        }
    }
}