<?php
/**
 * Created by PhpStorm.
 * User: Demix
 * Date: 07.04.2022
 * Time: 22:39
 */

namespace app\components;

use app\modules\stde\models\State;
use app\modules\stde\models\StateProperty;
use app\modules\stde\models\Transition;
use app\modules\stde\models\TransitionProperty;


class StateTransitionCSVloader
{
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
     * Нахождение индекса столбца переходов.
     *
     * @param $csv - массив созданный из $csv файла
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function searchColumnTransitions($csv)
    {
        //количество столбцов в массиве
        $count_column = count($csv[0]);

        //просматриваем столбцы с конца в начало
        for ($i = $count_column-1; $i >= 0; $i--) {
            //поиск ячейки с # в начале
            if(mb_substr($csv[0][$i], 0, 1)=='#'){
                //индекс столбца ячейки с #
                $number_transition_column  = $i;
            }
        }
        //индекс столбца переходов идет раньше ячейки с #
        $number_transition_column--;

        return $number_transition_column ;
    }


    //получаем значения StateProperty разбирая csv массив
    public function parsingCSV($csv)
    {
        $column_transitions = self::searchColumnTransitions($csv);

        $row = count($csv);//количество строк в csv массиве

        $states = [];//массив со значениями StateProperty

        //просмотр всех строк столбца значений StateProperty
        for ($i = 0; $i <= $row-1; $i++) {
            //добавляем значение StateProperty в новый массив
            if ($i == 0){
                $states[$i][0] = mb_substr($csv[$i][$column_transitions-1], mb_strpos($csv[$i][$column_transitions-1], '::') + mb_strlen('::'));//наименования StateProperty (текст после ::)
            } else {
                $states[$i][0] = $csv[$i][$column_transitions-1];//значение StateProperty
            }
        }

        //удаляем дубли в массиве
        $tmp_states = $key_array = array();
        $i = 0;
        foreach($states as $val) {
            if (!in_array($val[0], $key_array)) {
                $key_array[$i] = $val[0];
                $tmp_states[$i] = $val;
            }
            $i++;
        }
        //сдвигаем индексы массива
        $states = array_values($tmp_states);

        return $states;
    }


    public function uploadCSV($id, $csv)
    {
        self::cleanDiagram($id);

        $array = self::parsingCSV($csv);

        $state_name = mb_substr($csv[0][0], 0, mb_strpos($csv[0][0], '::'));//наименование State (текст до ::)

        $row = count($array);//количество строк в массиве
        $col = count($array[0]);//количество столбцов в массиве

        //создаем State и StateProperty
        for ($i = 1; $i <= $row-1; $i++) {
            //создаем State
            $state = new State();
            $state->name = $state_name;
            if ($i == 1){
                $state->type = State::INITIAL_STATE_TYPE;
            } else {
                $state->type = State::COMMON_STATE_TYPE;
            }
            $state->description = '';
            $state->indent_x = '0';
            $state->indent_y = '0';
            $state->diagram = $id;
            $state->save();
            $state_id = $state->id;

            //добавляем id созданного state в конец массива
            $array[$i][$col] = $state_id;

            //строим StateProperty на основе значений
            if(isset($array[$i][0])) {
                $state_property = new StateProperty();
                $state_property->name = $array[0][0]; //верхняя ячейка массива с названием StateProperty
                $state_property->description = '';
                $state_property->operator = StateProperty::EQUALLY_OPERATOR;
                $state_property->value = $array[$i][0];//ячейка со значением StateProperty
                $state_property->state = $state_id;
                $state_property->save();
            }
        }


        //построение связей Transition
        $column_transitions = self::searchColumnTransitions($csv);

        $row_csv = count($csv); //количество строк в массиве $csv
        $col_csv = count($csv[0]); //количество столбцов в массиве $csv

        for ($i = 1; $i <= $row_csv-1; $i++) {
            //проверка есть ли связь (откуда $csv[$i][$column_transitions-1])(куда $csv[$i][$column_transitions+1])
            if (($csv[$i][$column_transitions-1] != null) and ($csv[$i][$column_transitions+1] != null)){

                //поиск id state откуда
                for ($j = 1; $j <= $row-1; $j++) {
                    if($array[$j][0] == $csv[$i][$column_transitions-1]){
                        $state_from = $array[$j][$col];
                    }
                }
                //поиск id state куда
                for ($j = 1; $j <= $row-1; $j++) {
                    if($array[$j][0] == $csv[$i][$column_transitions+1]){
                        $state_to = $array[$j][$col];
                    }
                }

                //создаем Transition
                $transition = new Transition();
                $transition->name = mb_substr($csv[0][$column_transitions], 0, mb_strpos($csv[0][$column_transitions], '::')); //наименование Transition (текст до ::)
                $transition->description = '';
                $transition->state_from = $state_from;
                $transition->state_to = $state_to;
                $transition->name_property = 'Условие 1';
                $transition->operator_property = 0;
                $transition->value_property = '111';
                $transition->save();
                $transition_id = $transition->id;

                //просматриваем колонки в поисках значений TransitionProperty
                for ($c = 0; $c <= $col_csv-1; $c++) {
                    //кроме
                    if (($c != $column_transitions-1) and ($c != $column_transitions+1)){
                        //создаем TransitionProperty
                        $transition_property = new TransitionProperty();
                        $transition_property->name = mb_substr($csv[0][$c], mb_strpos($csv[0][$c], '::') + mb_strlen('::'));//наименование TransitionProperty (текст после ::)
                        $transition_property->description = '';
                        $transition_property->operator = TransitionProperty::EQUALLY_OPERATOR;
                        if ($csv[$i][$c] == null){
                            $transition_property->value = 0; //----------??????? значение с 0 не создается
                        } else {
                            $transition_property->value = $csv[$i][$c];
                        }
                        $transition_property->transition = $transition_id;
                        $transition_property->save();
                    }
                }
            }
        }
    }
}