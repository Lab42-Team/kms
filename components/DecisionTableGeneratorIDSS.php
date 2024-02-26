<?php


namespace app\components;


use app\modules\stde\models\State;
use app\modules\stde\models\StateProperty;
use app\modules\stde\models\Transition;
use app\modules\stde\models\TransitionProperty;


class DecisionTableGeneratorIDSS
{
    public static function generate($id)
    {
        $states = State::find()->where(['diagram' => $id])->all();
        $array_states = array();
        foreach ($states as $state) {
            $array_states[$state->id] = [];
            $array_states[$state->id]['Наименование'] = $state->name;
            $state_properties = StateProperty::find()->where(['state' => $state->id])->all();
            foreach ($state_properties as $state_property)
                $array_states[$state->id][$state_property->name] = $state_property->value;
        }

        $array_transitions = array();
        $transitions = Transition::find()->all();
        foreach ($transitions as $transition) {
            $array_current_transition = [];
            $array_current_transition['state-from'] = $transition->state_from;

            $transition_properties = TransitionProperty::find()->where(['transition' => $transition->id])->all();
            foreach ($transition_properties as $transition_property)
                $array_current_transition[$transition_property->name] = $transition_property->value;
            $array_current_transition['state-to'] = $transition->state_to;
            $array_transitions[] = $array_current_transition;
        }

        $array_transitions = array_unique($array_transitions, SORT_REGULAR);

        // precossing transitions
        //create header list
        $array_headers = array();
        //create array of rows
        $array_rows = array();
        $row = array();

        foreach ($array_transitions as $k => $v) {
            $row = [];

            foreach ($v as $k0 => $v0) {
                if ($k0 == 'state-from') {
                    $state_from = $v0;
                    //process state-from
                    $prefix = 'State::';
                    if (isset($lookup_table[$state_from]))
                        foreach ($array_states[$state_from] as $k1 => $v1) {
                            if (in_array($prefix . $k1, $array_headers) == false) array_push($array_headers, $prefix . $k1); //add new header
                            $row[$prefix . $k1] = $v1; //add new cell to row
                        }
                }

                if (($k0 != 'state-from') and ($k0 != 'state-to')) {
                    $prefix = 'State::';
                    if (in_array($prefix . $k0, $array_headers) == false) array_push($array_headers, $prefix . $k0); //add new header
                    $row[$prefix . $k0] = $v0; //add new cell to row
                }

                if ($k0 == 'state-to') {
                    $state_to = $v0;
                    //process state-from
                    $prefix = '#State::';
                    if (isset($lookup_table[$state_to]))
                        foreach ($array_states[$state_to] as $k1 => $v1) {
                            if (in_array($prefix . $k1, $array_headers) == false) array_push($array_headers, $prefix . $k1); //add new header
                            $row[$prefix . $k1] = $v1; //add new cell to row
                        }
                }
            }
            array_push($array_rows, $row);
        }

        //перебор заголовков и формирования строк для csv
        $towrite_headers = '';
        foreach ($array_headers as $k0 => $v0) {
            $towrite_headers .= $v0 . ';';
        }
        $towrite_rows = '';

        // rsort($array_headers);

        foreach ($array_rows as $k => $v) {
            $towrite_row = '';
            foreach ($array_headers as $k0 => $v0) {
                if (isset($v[$v0]))
                    $towrite_row .= $v[$v0] . ';';
            }
            $towrite_rows .= $towrite_row . "\n";
        }

        $name = 'export.csv';
        $fp = fopen($name,'wb');
        fwrite($fp, $towrite_headers . "\n" . $towrite_rows);
        fclose($fp);

        header('Content-type: text/csv');
        header("Content-Disposition: inline; filename=".$name);
        readfile($name);

        exit;
    }
}