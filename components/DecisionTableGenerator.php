<?php

namespace app\components;

use app\modules\stde\models\State;
use app\modules\stde\models\StateProperty;
use app\modules\stde\models\Transition;
use app\modules\stde\models\TransitionProperty;


class DecisionTableGenerator
{
    //поиск значения StateProperty
    public static function findStatePropertyValue($id_state)
    {
        $value = null;
        $state_property_element = StateProperty::find()->where(['state' => $id_state])->one();
        if ($state_property_element != null){
            $value = $state_property_element->value;
        }

        return $value;
    }

    //поиск наименования StateProperty
    public static function findStatePropertyName($id_state)
    {
        $value = null;
        $state_property_element = StateProperty::find()->where(['state' => $id_state])->one();
        if ($state_property_element != null){
            $value = $state_property_element->name;
        }

        return $value;
    }

    //построение массива значений
    public function buildingArray($id)
    {
        $array  = [];

        $state_elements = State::find()->where(['diagram' => $id])->all();

        //подбор всех Transition диаграммы
        $transition_all = Transition::find()->all();
        $transition_elements = array();//массив связей
        foreach ($transition_all as $t){
            foreach ($state_elements as $s){
                if ($t->state_from == $s->id){
                    array_push($transition_elements, $t);
                }
            }
        }


        //поиск списка наименований TransitionProperty
        $transition_property_names  = array();
        $transition_property_all = TransitionProperty::find()->all();

        if (($transition_property_all != null) and ($transition_elements != null)) {
            foreach ($transition_property_all as $tp){
                foreach ($transition_elements as $t){
                    if ($t->id == $tp->transition){
                        array_push($transition_property_names, $tp->name);
                    }
                }
            }
        }

        //удаляем дубли в массиве
        $transition_property_names = array_unique($transition_property_names);
        //удаляем пустые значения из массива
        $transition_property_names = array_map(null, $transition_property_names);
        $transition_property_names = array_filter( $transition_property_names );

        //сдвигаем индексы массива
        $transition_property_names = array_values($transition_property_names);


        //количество наименований TransitionProperty
        $count = count($transition_property_names);



        $state = State::find()->where(['diagram' => $id])->one();
        //наименование State
        $state_name = $state->name;
        //наименование StateProperty
        $state_property_name = self::findStatePropertyName($state->id);


        //проверка на пустое значение
        $blank = false;


        //заполнение первой строки массива наименованиями (заголовками таблицами)
        $i = 0;
        for ($j = 0; $j <= $count-1; $j++) {
            $array[$i][$j] = $transition_property_names[$j];
        }
        $array[$i][$count] = $state_property_name;
        $array[$i][$count+1] = $state_property_name;

        $i++;

        //заполнение остальных строк массива значениями из переходов
        if ($transition_elements != null) {
            foreach ($transition_elements as $t_elem){
                //поиск TransitionProperty текущего Transition
                $transition_property_elements = TransitionProperty::find()->where(['transition' => $t_elem->id])->all();
                if ($transition_property_elements != null) {
                    for ($j = 0; $j <= $count-1; $j++) {
                        foreach ($transition_property_elements as $tp){
                            //если верхний элемент (заголовок таблицы) соответствует наименованию TransitionProperty
                            if ($array[0][$j] == $tp->name){
                                //то ячейке присваиваем значение TransitionProperty
                                $array[$i][$j] = $tp->value;
                                $blank = true;
                            }
                        }
                        //если ни одной ячейке нет значения из бд то значение присваиваем пустое
                        if ($blank == false){
                            $array[$i][$j] = '';
                        }
                        $blank = false;
                    }
                }

                //присваиваем значение StateProperty
                $array[$i][$count] = self::findStatePropertyValue($t_elem->state_from);
                $array[$i][$count+1] = self::findStatePropertyValue($t_elem->state_to);

                $i++;
            }
        }

        //к значениям первой строки добавляем наименование State
        for ($j = 0; $j <= $count+1; $j++) {
            $array[0][$j] = $state_name . "::" . $array[0][$j];
        }

        //к значению первой строки последнего элемента добавляем #
        $array[0][$count+1] = "#" . $array[0][$count+1];


        return $array;
    }


    //генерация CSV кода
    public function generateCSVCode($id)
    {
        $array = self::buildingArray($id);

        $name = 'export.csv';
        $fp = fopen($name,'wb');
        foreach ($array as $fields) {
            fwrite($fp, implode(';', $fields) . "\r\n");
        }
        fclose($fp);

        header('Content-type: text/csv');
        header("Content-Disposition: inline; filename=".$name);
        readfile($name);

        exit;
    }
}