<?php

namespace app\components;

use DOMDocument;
use app\modules\main\models\Diagram;
use app\modules\stde\models\State;
use app\modules\stde\models\StateProperty;
use app\modules\stde\models\Transition;
use app\modules\stde\models\TransitionProperty;


class StateTransitionXMLGenerator
{

    public static function drawingStateProperty($xml, $id_state, $xml_element)
    {
        //подбор всех StateProperty
        $state_property_elements = StateProperty::find()->where(['state' => $id_state])->all();
        if ($state_property_elements != null){
            foreach ($state_property_elements as $sp_elem){
                //отрисовка "StateProperty"
                $state_property_element = $xml->createElement('StateProperty');
                $state_property_element->setAttribute('id', $sp_elem->id);
                $state_property_element->setAttribute('name', $sp_elem->name);
                $state_property_element->setAttribute('operator', $sp_elem->getOperatorName());
                $state_property_element->setAttribute('value', $sp_elem->value);
                $state_property_element->setAttribute('description', $sp_elem->description);
                $xml_element->appendChild($state_property_element);
            }
        }
    }

    public static function drawingTransitionProperty($xml, $id_transition, $xml_element)
    {
        //подбор всех TransitionProperty
        $transition_property_elements = TransitionProperty::find()->where(['transition' => $id_transition])->all();
        if ($transition_property_elements != null){
            foreach ($transition_property_elements as $tp_elem){
                //отрисовка "TransitionProperty"
                $transition_property_element = $xml->createElement('TransitionProperty');
                $transition_property_element->setAttribute('id', $tp_elem->id);
                $transition_property_element->setAttribute('name', $tp_elem->name);
                $transition_property_element->setAttribute('operator', $tp_elem->getOperatorName());
                $transition_property_element->setAttribute('value', $tp_elem->value);
                $transition_property_element->setAttribute('description', $tp_elem->description);
                $xml_element->appendChild($transition_property_element);
            }
        }
    }


    public function generateSTDXMLCode($id)
    {
        // Определение наименования файла
        $file = 'std_file.xml';
        if (!file_exists($file))
            fopen($file, 'w');

        // Создание документа DOM с кодировкой UTF8
        $xml = new DomDocument('1.0', 'UTF-8');
        $diagram = Diagram::find()->where(['id' => $id])->one();
        // Создание корневого узла Diagram
        $diagram_element = $xml->createElement('Diagram');
        $diagram_element->setAttribute('id', $diagram->id);
        $diagram_element->setAttribute('name', $diagram->name);
        $diagram_element->setAttribute('description', $diagram->description);

        // Добавление корневого узла Diagram в XML-документ
        $xml->appendChild($diagram_element);

        //подбор всех State
        $state_elements = State::find()->where(['diagram' => $id])->orderBy(['id' => SORT_ASC])->all();
        if ($state_elements != null) {
            foreach ($state_elements as $s_elem) {
                //Создание "State"
                $state_element = $xml->createElement('State');
                $state_element->setAttribute('id', $s_elem->id);
                $state_element->setAttribute('name', $s_elem->name);
                $state_element->setAttribute('type', $s_elem->getTypeNameEn());
                $state_element->setAttribute('description', $s_elem->description);
                $diagram_element->appendChild($state_element);

                //отрисовка "StateProperty"
                self::drawingStateProperty($xml, $s_elem->id, $state_element);
            }
        }

        //подбор всех Transition
        $transition_all = Transition::find()->all();
        $transition_elements = array();//массив связей
        foreach ($transition_all as $t){
            foreach ($state_elements as $s){
                if ($t->state_from == $s->id){
                    array_push($transition_elements, $t);
                }
            }
        }

        if ($transition_elements != null) {
            foreach ($transition_elements as $t_elem) {
                //Создание "Transition"
                $transition_element = $xml->createElement('Transition');
                $transition_element->setAttribute('id', $t_elem->id);
                $transition_element->setAttribute('name', $t_elem->name);
                $transition_element->setAttribute('left-state-id', $t_elem->state_from);
                $transition_element->setAttribute('right-state-id', $t_elem->state_to);
                $transition_element->setAttribute('description', $t_elem->description);
                $diagram_element->appendChild($transition_element);

                //отрисовка "TransitionProperty"
                self::drawingTransitionProperty($xml, $t_elem->id, $transition_element);
            }
        }


        // Сохранение RDF-файла
        $xml->formatOutput = true;
        header("Content-type: application/octet-stream");
        header('Content-Disposition: filename="'.$file.'"');
        echo $xml->saveXML();
        exit;
    }
}