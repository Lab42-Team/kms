<?php

namespace app\components;

use app\modules\main\models\Diagram;
use app\modules\eete\models\TreeDiagram;
use app\modules\eete\models\Level;
use app\modules\eete\models\Node;
use app\modules\eete\models\Parameter;
use app\modules\eete\models\Sequence;
use app\modules\stde\models\State;
use app\modules\stde\models\StateProperty;
use app\modules\stde\models\Transition;
use app\modules\stde\models\TransitionProperty;

/**
 * Class OWLOntologyImporter - Класс реализующий импорт OWL-онтологий в
 * диаграммы (деревья событий или переходов состояний).
 * @package app\components
 */
class OWLOntologyImporter
{
    /**
     * Очистка диаграммы дерева событий.
     *
     * @param $id - идентификатор диаграммы дерева событий
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public static function cleanEventTreeDiagram($id)
    {
        $nodes = Node::find()->where(['tree_diagram' => $id])->all();
        foreach ($nodes as $node)
            $node->delete();
    }

    /**
     * Очистка диаграммы переходов состояний.
     *
     * @param $id - идентификатор диаграммы переходов состояний
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public static function cleanStateTransitionDiagram($id)
    {
        $states = State::find()->where(['diagram' => $id])->all();
        foreach ($states as $state)
            $state->delete();
    }

    /**
     * Получение комментария (описания) онтологии.
     *
     * @param $xml_rows - XML-строки из OWL-файла онтологии
     * @return string|null - комментарий (описание) онтологии
     */
    public static function getOntologyDescription($xml_rows)
    {
        $comment = null;
        // Получение всех пространств имен объявленых в XML-документе онтологии
        $namespaces = $xml_rows->getDocNamespaces(true);
        foreach ($namespaces as $prefix => $namespace)
            // Обход всех тегов внутри корневого элемента с учетом пространства имен
            foreach ($xml_rows->children($namespaces[$prefix]) as $element)
                // Если текущий элемент является тегом онтологии
                if ($element->getName() == 'Ontology')
                    foreach ($namespaces as $prefix => $namespace)
                        // Обход всех тегов внутри элемента тега онтологии с учетом пространства имен
                        foreach ($element->children($namespaces[$prefix]) as $child)
                            // Если текущий элемент является комментарием к онтологии
                            if ($child->getName() == 'comment')
                                $comment = (string)$element->comment;

       return $comment;
    }

    /**
     * Получение массива всех классов с комментариями из онтологии.
     *
     * @param $xml_rows - XML-строки из OWL-файла онтологии
     * @return array - массив всех классов с комментариями (описанием класса), извлеченных из онтологии
     */
    public function getClasses($xml_rows)
    {
        // Массив для хранения извлекаемых классов
        $classes = array();
        // Получение всех пространств имен объявленых в XML-документе онтологии
        $namespaces = $xml_rows->getDocNamespaces(true);
        // Обход всех тегов внутри корневого элемента с учетом пространства имен
        foreach ($namespaces as $prefix => $namespace)
            foreach ($xml_rows->children($namespaces[$prefix]) as $element) {
                $class = null;
                $comment = null;
                // Обход всех атрибутов данного элемента с учетом пространства имен
                foreach ($namespaces as $prefix => $namespace)
                    foreach ($element->attributes($prefix, true) as $attribute_name => $attribute_value)
                        // Если текущий элемент является классом с определенным атрибутом
                        if ($element->getName() == 'Class' && $attribute_name == 'about') {
                            // Извлечение значения атрибута у текущего элемента (класса онтологии)
                            $array = explode('#', (string)$attribute_value);
                            $class = $array[1];
                        }
                if ($element->getName() == 'Class')
                    foreach ($namespaces as $prefix => $namespace)
                        // Обход всех тегов внутри элемента класса с учетом пространства имен
                        foreach ($element->children($namespaces[$prefix]) as $child)
                            // Если текущий элемент является комментарием
                            if ($child->getName() == 'comment')
                                $comment = (string)$child;
                // Формирование массива классов с комментариями
                if (isset($class))
                    array_push($classes, [$class, $comment]);
            }

        return $classes;
    }

    /**
     * Получение массива свойств-знаечний для всех классов из онтологии.
     *
     * @param $xml_rows - XML-строки из OWL-файла онтологии
     * @return array - массив свойств-знаечний для всех классов онтологии
     */
    public function getDatatypeProperties($xml_rows)
    {
        // Массив для хранения извлекаемых свойств-значений
        $datatype_properties = array();
        // Получение всех пространств имен объявленых в XML-документе онтологии
        $namespaces = $xml_rows->getDocNamespaces(true);
        // Обход всех тегов внутри корневого элемента с учетом пространства имен
        foreach ($namespaces as $prefix => $namespace)
            foreach ($xml_rows->children($namespaces[$prefix]) as $element) {
                // Обход всех атрибутов данного элемента с учетом пространства имен
                foreach ($namespaces as $prefix => $namespace)
                    foreach ($element->attributes($prefix, true) as $attribute_name => $attribute_value)
                        // Если текущий элемент является свойством-значения с определенным атрибутом
                        if ($element->getName() == 'DatatypeProperty' && $attribute_name == 'about') {
                            // Извлечение значения атрибута у текущего элемента (свойства-значения класса)
                            $datatype_property = explode('#', (string)$attribute_value);
                            // Обход всех тегов внутри элемента с учетом пространства имен
                            foreach ($namespaces as $prefix => $namespace)
                                foreach ($element->children($namespaces[$prefix]) as $child)
                                    // Обход всех атрибутов данного элемента с учетом пространства имен
                                    foreach ($namespaces as $prefix => $namespace)
                                        foreach ($child->attributes($prefix, true) as $attribute_name1 => $attribute_value1)
                                            // Если совпадает название искомого элемента и его атрибута
                                            if ($child->getName() == 'domain' && $attribute_name1 == 'resource') {
                                                // Извлечение значения атрибута у текущего элемента (класса онтологии)
                                                $class = explode('#', (string)$attribute_value1);
                                                $datatype_properties[$class[1]] = [[$datatype_property[1], null]];
                                            }
                        }
                // Если текущий элемент является классом
                if ($element->getName() == 'Class') {
                    $class = null;
                    foreach ($namespaces as $prefix => $namespace) {
                        // Извлечение значения атрибута у текущего элемента (класса онтологии)
                        foreach ($element->attributes($prefix, true) as $attribute_name => $attribute_value)
                            if ($attribute_name == 'about')
                                $class = explode('#', (string)$attribute_value);
                        // Обход всех тегов внутри элемента класса с учетом пространства имен
                        foreach ($element->children($namespaces[$prefix]) as $sub_class)
                            if ($sub_class->getName() == 'subClassOf') {
                                $datatype_property_name = null;
                                $datatype_property_value = null;
                                foreach ($namespaces as $prefix => $namespace)
                                    // Обход всех тегов внутри элемента подкласса с учетом пространства имен
                                    foreach ($sub_class->children($namespaces[$prefix]) as $restriction)
                                        if ($restriction->getName() == 'Restriction')
                                            foreach ($namespaces as $prefix => $namespace)
                                                // Обход всех тегов внутри элемента ограничения подкласса с учетом пространства имен
                                                foreach ($restriction->children($namespaces[$prefix]) as $property) {
                                                    // Определение значения для свойства-значения
                                                    if ($property->getName() == 'hasValue')
                                                        $datatype_property_value = (string)$restriction->hasValue;
                                                    foreach ($namespaces as $prefix => $namespace)
                                                        // Обход всех атрибутов данного элемента с учетом пространства имен
                                                        foreach ($property->attributes($prefix, true) as $attribute_name => $attribute_value) {
                                                            // Определение названия свойства-значения
                                                            if ($property->getName() == 'onProperty' &&
                                                                $attribute_name == 'resource') {
                                                                $array = explode('#', (string)$attribute_value);
                                                                foreach ($datatype_properties as $items)
                                                                    foreach ($items as $item)
                                                                        if ($item[0] == $array[1])
                                                                            $datatype_property_name = $array[1];
                                                            }
                                                            // Определение значения для свойства-значения
                                                            if ($property->getName() == 'hasValue' &&
                                                                $attribute_name == 'resource') {
                                                                $array = explode('#', (string)$attribute_value);
                                                                $datatype_property_value = $array[1];
                                                            }
                                                        }
                                                }
                                // Добавление свойства-значения для класса
                                if (isset($class[1]) && isset($datatype_property_name))
//                                    if (isset($datatype_properties[$class[1]])) {
//                                        $item = $datatype_properties[$class[1]];
//                                        array_push($item, [$datatype_property_name, $datatype_property_value]);
//                                        $datatype_properties[$class[1]] = [[$datatype_property_name, 'VVV']];//$item;
//                                    } else {
                                        $datatype_properties[$class[1]] = [[$datatype_property_name,
                                            $datatype_property_value]];
//                                    }
                            }
                    }
                }
            }

        return $datatype_properties;
    }

    /**
     * Получение массива объектных свойств для всех классов из онтологии.
     *
     * @param $xml_rows - XML-строки из OWL-файла онтологии
     * @return array - массив объектных свойств
     */
    public function getObjectProperties($xml_rows)
    {
        $object_property_names = array();
        // Массив для хранения извлекаемых объектных свойств
        $object_properties = array();
        // Получение всех пространств имен объявленых в XML-документе онтологии
        $namespaces = $xml_rows->getDocNamespaces(true);
        // Обход всех тегов внутри корневого элемента с учетом пространства имен
        foreach ($namespaces as $prefix => $namespace)
            foreach ($xml_rows->children($namespaces[$prefix]) as $element) {
                // Обход всех атрибутов данного элемента с учетом пространства имен
                foreach ($namespaces as $prefix => $namespace)
                    foreach ($element->attributes($prefix, true) as $attribute_name => $attribute_value)
                        // Если текущий элемент является объектным свойством с определенным атрибутом
                        if ($element->getName() == 'ObjectProperty' && $attribute_name == 'about') {
                            $domain_class = null;
                            $range_class = array();
                            // Извлечение значения атрибута у текущего элемента (объектного свойства)
                            $array = explode('#', (string)$attribute_value);
                            //
                            array_push($object_property_names, $array[1]);
                            $object_property_name = $array[1];
                            // Обход всех тегов внутри элемента с учетом пространства имен
                            foreach ($namespaces as $prefix => $namespace)
                                foreach ($element->children($namespaces[$prefix]) as $child)
                                    // Обход всех атрибутов данного элемента с учетом пространства имен
                                    foreach ($namespaces as $prefix => $namespace)
                                        foreach ($child->attributes($prefix, true) as $attribute_name => $attribute_value) {
                                            // Определение класса "слева" в отношении
                                            if ($child->getName() == 'domain' && $attribute_name == 'resource') {
                                                $array = explode('#', (string)$attribute_value);
                                                $domain_class = $array[1];
                                            }
                                            // Определение классов "справа" в отношении
                                            if ($child->getName() == 'range' && $attribute_name == 'resource') {
                                                $array = explode('#', (string)$attribute_value);
                                                array_push($range_class, $array[1]);
                                            }
                                        }
                            // Формирование массива объектных свойств (отношений между классами)
                            if (isset($domain_class))
                                $object_properties[$object_property_name] = [$domain_class, $range_class];
                        }
                // Если текущий элемент является классом
                if ($element->getName() == 'Class') {
                    $domain_class = null;
                    $range_class = array();
                    foreach ($namespaces as $prefix => $namespace) {
                        // Извлечение значения атрибута у текущего элемента (класса онтологии)
                        foreach ($element->attributes($prefix, true) as $attribute_name => $attribute_value)
                            if ($attribute_name == 'about') {
                                $array = explode('#', (string)$attribute_value);
                                $domain_class = $array[1];
                            }
                        // Обход всех тегов внутри элемента класса с учетом пространства имен
                        foreach ($element->children($namespaces[$prefix]) as $sub_class)
                            if ($sub_class->getName() == 'subClassOf')
                                foreach ($namespaces as $prefix => $namespace)
                                    // Обход всех тегов внутри элемента подкласса с учетом пространства имен
                                    foreach ($sub_class->children($namespaces[$prefix]) as $restriction)
                                        if ($restriction->getName() == 'Restriction') {
                                            $is_obj_prop = false;
                                            foreach ($namespaces as $prefix => $namespace)
                                                // Обход всех тегов внутри элемента ограничения подкласса с учетом пространства имен
                                                foreach ($restriction->children($namespaces[$prefix]) as $property)
                                                    foreach ($namespaces as $prefix => $namespace)
                                                        // Обход всех атрибутов данного элемента с учетом пространства имен
                                                        foreach ($property->attributes($prefix, true) as $attribute_name => $attribute_value) {
                                                            // Определение названия объектного свойства
                                                            if ($property->getName() == 'onProperty' &&
                                                                $attribute_name == 'resource') {
                                                                $array = explode('#', (string)$attribute_value);
                                                                foreach ($object_property_names as $object_property_name)
                                                                    if ($object_property_name == $array[1])
                                                                        $is_obj_prop = true;
                                                            }
                                                            // Определение значения для свойства-значения
                                                            if ($property->getName() == 'someValuesFrom' &&
                                                                $attribute_name == 'resource' && $is_obj_prop) {
                                                                $array = explode('#', (string)$attribute_value);
                                                                array_push($range_class, $array[1]);
                                                            }
                                                        }
                                        }
                        // Добавление в массив нового отношения
                        if (!empty($range_class)) {
                            $index = null;
                            $new_item = null;
                            foreach ($object_properties as $key => $object_property)
                                if ($object_property[0] == $domain_class) {
                                    $item = $object_property[1];
                                    $range_class_exist = false;
                                    foreach ($range_class as $class) {
                                        foreach ($item as $value)
                                            if ($class == $value)
                                                $range_class_exist = true;
                                        if ($range_class_exist == false)
                                            array_push($item, $class);
                                    }
                                    $new_item = [$object_property[0], $item];
                                    $index = $key;
                                }
                            if (isset($new_item) && isset($index))
                                $object_properties[$index] = $new_item;
                            else
                                array_push($object_properties, [$domain_class, $range_class]);
                        }
                    }
                }
            }

        return $object_properties;
    }

    /**
     * Получение массива иерархических отношений между классами из онтологии.
     *
     * @param $xml_rows - XML-строки из OWL-файла онтологии
     * @return array - массив иерархических отношений между классами
     */
    public function getSubClasses($xml_rows)
    {
        // Массив для хранения извлекаемых иерархических отношений между классами
        $subclasses = array();
        // Получение всех пространств имен объявленых в XML-документе онтологии
        $namespaces = $xml_rows->getDocNamespaces(true);
        foreach ($namespaces as $prefix => $namespace)
            // Обход всех тегов внутри корневого элемента с учетом пространства имен
            foreach ($xml_rows->children($namespaces[$prefix]) as $element) {
                $class = null;
                $subclass = null;
                foreach ($namespaces as $prefix => $namespace) {
                    // Обход всех атрибутов данного элемента с учетом пространства имен
                    foreach ($element->attributes($prefix, true) as $attribute_name => $attribute_value)
                        // Если текущий элемент является классом сопределенным атрибутом
                        if ($element->getName() == 'Class' && $attribute_name == 'about') {
                            // Извлечение значения атрибута у текущего элемента (класса онтологии)
                            $array = explode('#', (string)$attribute_value);
                            $subclass = $array[1];
                        }
                    // Обход всех тегов внутри элемента класса с учетом пространства имен
                    foreach ($element->children($namespaces[$prefix]) as $child)
                        foreach ($namespaces as $prefix => $namespace)
                            // Обход всех атрибутов данного элемента с учетом пространства имен
                            foreach ($child->attributes($prefix, true) as $attribute_name => $attribute_value)
                                // Если текущий элемент является подклассом сопределенным атрибутом
                                if ($child->getName() == 'subClassOf' && $attribute_name == 'resource') {
                                    // Извлечение значения атрибута у текущего элемента (класса онтологии)
                                    $array = explode('#', (string)$attribute_value);
                                    $class = $array[1];
                                }
                }
                // Формирование массива с классами и их подклассами
                if (isset($class)) {
                    if (isset($subclasses[$class])) {
                        $item = $subclasses[$class];
                        array_push($item, $subclass);
                        $subclasses[$class] = $item;
                    } else
                        $subclasses[$class] = [$subclass];
                }
            }

        return $subclasses;
    }

    /**
     * Получение массива всех индивидов (экземпляров классов) с их свойствами и комментариями из онтологии.
     *
     * @param $xml_rows - XML-строки из OWL-файла онтологии
     * @return array - массив всех индивидов извлеченных из онтологии, содержащих комментарии (описания индивидов),
     * ссылки на их классы, свойства-значений и объектные свойства (отношения)
     */
    public function getIndividuals($xml_rows)
    {
        // Массив для хранения извлекаемых индивидов
        $individuals = array();
        // Получение всех пространств имен объявленых в XML-документе онтологии
        $namespaces = $xml_rows->getDocNamespaces(true);
        // Обход всех тегов внутри корневого элемента с учетом пространства имен
        foreach ($namespaces as $prefix => $namespace)
            foreach ($xml_rows->children($namespaces[$prefix]) as $element) {
                $individual_name = null;
                $comment = null;
                $type = null;
                $datatype_properties = array();
                $object_properties = array();
                // Обход всех атрибутов данного элемента с учетом пространства имен
                foreach ($namespaces as $prefix => $namespace)
                    foreach ($element->attributes($prefix, true) as $attribute_name => $attribute_value)
                        // Если текущий элемент является индивидом с определенным атрибутом
                        if ($element->getName() == 'NamedIndividual' && $attribute_name == 'about') {
                            // Извлечение значения атрибута у текущего элемента (индивида онтологии)
                            $array = explode('#', (string)$attribute_value);
                            $individual_name = $array[1];
                        }
                // Если текуший элемент является индивидом
                if ($element->getName() == 'NamedIndividual')
                    foreach ($namespaces as $prefix => $namespace)
                        // Обход всех тегов внутри элемента индивида с учетом пространства имен
                        foreach ($element->children($namespaces[$prefix]) as $child) {
                            // Если текущий элемент является комментарием
                            if ($child->getName() == 'comment')
                                $comment = (string)$child;
                            // Если текущий элемент является ссылкой на класс
                            if ($child->getName() == 'type')
                                // Определение класса для индивида по ссылке в атрибуте элемента
                                foreach ($namespaces as $prefix => $namespace)
                                    foreach ($child->attributes($prefix, true) as $attribute_name => $attribute_value)
                                        if ($attribute_name == 'resource') {
                                            $array = explode('#', (string)$attribute_value);
                                            $type = $array[1];
                                        }
                            // Если текущий элемент не является комментарием и ссылкой на класс
                            if ($child->getName() != 'comment' and $child->getName() != 'type') {
                                $attribute_exist = false;
                                // Определение объектного свойства для индивида
                                foreach ($namespaces as $prefix => $namespace)
                                    foreach ($child->attributes($prefix, true) as $attribute_name => $attribute_value)
                                        if ($attribute_name == 'resource') {
                                            $attribute_exist = true;
                                            $array = explode('#', (string)$attribute_value);
                                            $object_properties[$child->getName()] = $array[1];
                                        }
                                // Определение свойства-значения для индивида
                                if ($attribute_exist == false)
                                    $datatype_properties[$child->getName()] = (string)$child;
                            }
                        }
                // Формирование массива индивидов с комментарием, ссылкой на его класс,
                // свойствами-значениями и объектными свойствами
                if (isset($individual_name))
                    $individuals[$individual_name] = [$comment, $type, $datatype_properties, $object_properties];
            }

        return $individuals;
    }

    /**
     * Конвертация OWL-онтологии в диаграмму дерева событий.
     *
     * @param $id - идентификатор диаграммы
     * @param $xml_rows - XML-строки из OWL-файла онтологии
     * @param $selected_classes - массив классов, выбранных пользователем
     * @param $hierarchy - индикатор интерпретации иерархических связей
     * @param $relation - индикатор интерпретации связеймежду классами
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function convertOWLOntologyToEventTreeDiagram($id, $xml_rows, $selected_classes, $hierarchy, $relation)
    {
        // Удаление всех узлов на диаграмме дерева событий
        self::cleanEventTreeDiagram($id);

        // Получение комментария (описания) онтологии
        $comment = self::getOntologyDescription($xml_rows);
        // Обновление описания дерева событий
        $tree_diagram = TreeDiagram::findOne($id);
        $tree_diagram->description = $comment;
        $tree_diagram->save();

        // Поиск уровня у данной диаграммы дерева событий
        $level = Level::find()->where(['tree_diagram' => $tree_diagram->id])->one();

        $number = 1;
        // Получение массива всех классов с комментариями из онтологии
        $classes = self::getClasses($xml_rows);
        // Получение массива свойств-знаечний для всех классов из онтологии
        $datatype_properties = self::getDatatypeProperties($xml_rows);
        // Обход выбранных пользователем классов
        foreach ($selected_classes as $selected_class) {
            // Обход всех классов, извлеченных из онтологии
            foreach ($classes as $item)
                // Если имена классов совпадают
                if ($selected_class == $item[0]) {
                    // Поиск улов (событий) в данном дереве событий
                    $nodes = Node::find()->where(['tree_diagram' => $tree_diagram->id])->all();
                    // Создание нового узла (события) дерева событий
                    $node_model = new Node();
                    $node_model->name = $item[0];
                    $node_model->description = $item[1];
                    $node_model->type = empty($nodes) ? Node::INITIAL_EVENT_TYPE : Node::EVENT_TYPE;
                    $node_model->operator = Node::AND_OPERATOR;
                    $node_model->indent_x = 0;
                    $node_model->indent_y = 0;
                    $node_model->level_id = $level->id;
                    $node_model->tree_diagram = $tree_diagram->id;
                    $node_model->save();
                    // Создание новой модели Sequence
                    $sequence_model = new Sequence();
                    $sequence_model->node = $node_model->id;
                    $sequence_model->level = $level->id;
                    $sequence_model->tree_diagram = $tree_diagram->id;
                    $sequence_model->priority = $number;
                    $sequence_model->save();
                    $number++;
                    // Обход всех свойств-значений классов, извлеченных из онтологии
                    foreach ($datatype_properties as $class => $items)
                        if ($selected_class == $class)
                            foreach ($items as $datatype_property) {
                                // Создание нового параметра для события дерева событий
                                $parameter_model = new Parameter();
                                $parameter_model->name = $datatype_property[0];
                                $parameter_model->value = $datatype_property[1];
                                $parameter_model->operator = Parameter::EQUALLY_OPERATOR;
                                $parameter_model->node = $node_model->id;
                                $parameter_model->save();
                            }
                }
        }

        $object_properties = array();
        // Если пользователь указал интерпретацию иерархических отношений
        if ($hierarchy) {
            // Получение массива иерархических отношений между классами из онтологии
            $subclasses = self::getSubClasses($xml_rows);
            // Обход всех иерархических отношений между классами
            foreach ($subclasses as $class => $current_subclasses)
                if (isset($current_subclasses))
                    foreach ($current_subclasses as $subclass) {
                        // Поиск классов имеющих иерархические отношения среди выбранных пользователем классов
                        $class_exists = false;
                        $subclass_exists = false;
                        foreach ($selected_classes as $selected_class) {
                            if ($selected_class == $class)
                                $class_exists = true;
                            if ($selected_class == $subclass)
                                $subclass_exists = true;
                        }
                        // Если такие классы есть
                        if ($class_exists && $subclass_exists) {
                            // Поиск событий в БД
                            $parent_node = Node::find()->where(['name' => $class])->one();
                            $child_node = Node::find()->where(['name' => $subclass])->one();
                            // Если события найдены
                            if (!empty($parent_node) && !empty($child_node)) {
                                // Задание родительского события (отношения между событиями)
                                $child_node->parent_node = $parent_node->id;
                                $child_node->updateAttributes(['parent_node']);
                            }
                        }
                    }
        }

        // Если пользователь указал интерпретацию отношений между классами
        if ($relation) {
            // Получение массива объектных свойств для всех классов из онтологии
            $object_properties = self::getObjectProperties($xml_rows);
            // Обход всех отношений (объектных свойств) между классами
            foreach ($object_properties as $object_property) {
                // Поиск "левого" класса из отношения среди выбранных пользователем классов
                $domain_class_exists = false;
                foreach ($selected_classes as $selected_class) {
                    if ($selected_class == $object_property[0])
                        $domain_class_exists = true;
                }
                // Обход всех "правых" классов
                foreach ($object_property[1] as $range_class) {
                    // Поиск "правого" класса из отношения среди выбранных пользователем классов
                    $range_class_exists = false;
                    foreach ($selected_classes as $selected_class) {
                        if ($selected_class == $range_class)
                            $range_class_exists = true;
                    }
                    // Если такие классы есть
                    if ($domain_class_exists && $range_class_exists) {
                        // Поиск событий в БД
                        $parent_node = Node::find()->where(['name' => $object_property[0]])->one();
                        $child_node = Node::find()->where(['name' => $range_class])->one();
                        // Если события найдены
                        if (!empty($parent_node) && !empty($child_node)) {
                            // Задание родительского события (отношения между событиями)
                            $child_node->parent_node = $parent_node->id;
                            $child_node->updateAttributes(['parent_node']);
                        }
                    }
                }
            }
        }
    }

    /**
     * Конвертация OWL-онтологии в диаграмму переходов состояний.
     *
     * @param $id - идентификатор диаграммы переходов состояний
     * @param $xml_rows - XML-строки из OWL-файла онтологии
     * @param $class_flag - индикатор интерпретации классов
     * @param $class_property_flag - индикатор учета свойств-знаечний классов
     * @param $class_hierarchy_flag - индикатор учета иерархических отношений между классами
     * @param $class_relation_flag - индикатор учета объектных свойств классов (отношений между классами)
     * @param $individual_flag - индикатор интерпретации индивидов (экземпляров классов)
     * @param $individual_property_flag - индикатор учета свойств-знаечний индивидов
     * @param $individual_is_a_flag - индикатор учета отношений между индивидом и его классом
     * @param $individual_relation_flag - - индикатор учета объектных свойств индивидов (отношений между индивидами)
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function convertOWLOntologyToStateTransitionDiagram($id, $xml_rows, $class_flag, $class_property_flag,
                                                               $class_hierarchy_flag, $class_relation_flag,
                                                               $individual_flag, $individual_property_flag,
                                                               $individual_is_a_flag, $individual_relation_flag)
    {
        // Удаление всех узлов на диаграмме переходов состояний
        self::cleanStateTransitionDiagram($id);

        // Получение комментария (описания) онтологии
        $comment = self::getOntologyDescription($xml_rows);
        // Обновление описания диаграммы переходов состояний
        $diagram = Diagram::findOne($id);
        $diagram->description = $comment;
        $diagram->save();

        // Если пользователь указал интерпретацию классов
        if ($class_flag) {
            // Получение массива всех классов с комментариями из онтологии
            $classes = self::getClasses($xml_rows);
            // Если пользователь указал учет свойств-знаечний классов
            if ($class_property_flag)
                // Получение массива свойств-знаечний для всех классов из онтологии
                $datatype_properties = self::getDatatypeProperties($xml_rows);
            // Обход всех классов, извлеченных из онтологии
            foreach ($classes as $item) {
                // Поиск состояний в данной диаграмме переходов состояний
                $states = State::find()->where(['diagram' => $diagram->id])->all();
                // Создание нового состояния
                $state_model = new State();
                $state_model->name = $item[0];
                $state_model->type = empty($states) ? State::INITIAL_STATE_TYPE : State::COMMON_STATE_TYPE;
                $state_model->description = $item[1];
                $state_model->indent_x = 0;
                $state_model->indent_y = 0;
                $state_model->diagram = $diagram->id;
                $state_model->save();
                // Если пользователь указал учет свойств-знаечний классов
                if ($class_property_flag)
                    // Обход всех свойств-значений классов, извлеченных из онтологии
                    foreach ($datatype_properties as $class => $items)
                        if ($item[0] == $class)
                            foreach ($items as $datatype_property) {
                                // Создание нового свойства для состояния
                                $state_property_model = new StateProperty();
                                $state_property_model->name = $datatype_property[0];
                                $state_property_model->value = $datatype_property[1] != null ? $datatype_property[1] :
                                    'None';
                                $state_property_model->operator = StateProperty::EQUALLY_OPERATOR;
                                $state_property_model->state = $state_model->id;
                                $state_property_model->save();
                            }
            }

            // Если пользователь указал учет иерархических отношений между классами
            if ($class_hierarchy_flag) {
                $relation_index = 1;
                // Получение массива иерархических отношений между классами из онтологии
                $subclasses = self::getSubClasses($xml_rows);
                // Обход всех иерархических отношений между классами
                foreach ($subclasses as $parent_class => $current_subclasses)
                    if (isset($current_subclasses))
                        foreach ($current_subclasses as $subclass) {
                            // Поиск классов имеющих иерархические отношения среди всех классов
                            $parent_class_exists = false;
                            $subclass_exists = false;
                            foreach ($classes as $item) {
                                if ($item[0] == $parent_class)
                                    $parent_class_exists = true;
                                if ($item[0] == $subclass)
                                    $subclass_exists = true;
                            }
                            // Если такие классы есть
                            if ($parent_class_exists && $subclass_exists) {
                                // Поиск состояний в БД
                                $state_from = State::find()->where(['name' => $parent_class])->one();
                                $state_to = State::find()->where(['name' => $subclass])->one();
                                // Если состояния найдены
                                if (!empty($state_from) && !empty($state_to)) {
                                    // Создание нового перехода
                                    $transition_model = new Transition();
                                    $transition_model->name = 'relation-' . $relation_index;
                                    $transition_model->state_from = $state_from->id;
                                    $transition_model->state_to = $state_to->id;
                                    $transition_model->name_property = 'name property';
                                    $transition_model->operator_property = TransitionProperty::EQUALLY_OPERATOR;
                                    $transition_model->value_property = 'value property';
                                    $transition_model->save();
                                    // Увеличение индекса отношения
                                    $relation_index++;
                                }
                            }
                        }
            }

            // Если пользователь указал учет объектных свойств классов (отношений между классами)
            if ($class_relation_flag) {
                // Получение массива объектных свойств для всех классов из онтологии
                $object_properties = self::getObjectProperties($xml_rows);
                // Обход всех отношений (объектных свойств) между классами
                foreach ($object_properties as $object_property_name => $object_property) {
                    // Поиск "левого" класса из отношения среди выбранных пользователем классов
                    $domain_class_exists = false;
                    foreach ($classes as $item) {
                        if ($item[0] == $object_property[0])
                            $domain_class_exists = true;
                    }
                    // Обход всех "правых" классов
                    foreach ($object_property[1] as $range_class) {
                        // Поиск "правого" класса из отношения среди выбранных пользователем классов
                        $range_class_exists = false;
                        foreach ($classes as $item) {
                            if ($item[0] == $range_class)
                                $range_class_exists = true;
                        }
                        // Если такие классы есть
                        if ($domain_class_exists && $range_class_exists) {
                            // Поиск состояний в БД
                            $state_from = State::find()->where(['name' => $object_property[0]])->one();
                            $state_to = State::find()->where(['name' => $range_class])->one();
                            // Если состояния найдены
                            if (!empty($state_from) && !empty($state_to)) {
                                // Создание нового перехода
                                $transition_model = new Transition();
                                $transition_model->name = $object_property_name;
                                $transition_model->state_from = $state_from->id;
                                $transition_model->state_to = $state_to->id;
                                $transition_model->name_property = 'name property';
                                $transition_model->operator_property = TransitionProperty::EQUALLY_OPERATOR;
                                $transition_model->value_property = 'value property';
                                $transition_model->save();
                            }
                        }
                    }
                }
            }
        }

        // Если пользователь указал интерпретацию индивидов (экземпляров классов)
        if ($individual_flag) {
            // Получение массива всех индивидов с комментариями из онтологии
            $individuals = self::getIndividuals($xml_rows);
            // Обход всех классов, извлеченных из онтологии
            foreach ($individuals as $individual_name => $item) {
                // Поиск состояний в данной диаграмме переходов состояний
                $states = State::find()->where(['diagram' => $diagram->id])->all();
                // Создание нового состояния
                $state_model = new State();
                $state_model->name = $individual_name;
                $state_model->type = empty($states) ? State::INITIAL_STATE_TYPE : State::COMMON_STATE_TYPE;
                $state_model->description = $item[0];
                $state_model->indent_x = 0;
                $state_model->indent_y = 0;
                $state_model->diagram = $diagram->id;
                $state_model->save();
                // Если пользователь указал учет свойств-знаечний индивидов
                if ($individual_property_flag) {
                    // Обход массива свойств-значений индивида
                    foreach ($item[2] as $datatype_property_name => $datatype_property_value) {
                        // Создание нового свойства для состояния
                        $state_property_model = new StateProperty();
                        $state_property_model->name = $datatype_property_name;
                        $state_property_model->value = $datatype_property_value;
                        $state_property_model->operator = StateProperty::EQUALLY_OPERATOR;
                        $state_property_model->state = $state_model->id;
                        $state_property_model->save();
                    }
                }
                // Если пользователь указал учет отношения между классом и его индивидом
                if ($individual_is_a_flag) {
                    // Поиск состояний в БД
                    $state_from = State::find()->where(['name' => $individual_name])->one();
                    $state_to = State::find()->where(['name' => $item[1]])->one();
                    // Если состояния найдены
                    if (!empty($state_from) && !empty($state_to)) {
                        // Создание нового перехода
                        $transition_model = new Transition();
                        $transition_model->name = 'is a';
                        $transition_model->state_from = $state_from->id;
                        $transition_model->state_to = $state_to->id;
                        $transition_model->name_property = 'name property';
                        $transition_model->operator_property = TransitionProperty::EQUALLY_OPERATOR;
                        $transition_model->value_property = 'value property';
                        $transition_model->save();
                    }
                }
                // Если пользователь указал учет объектных свойств индивидов (отношений между индивидами)
                if ($individual_relation_flag) {
                    // Поиск состояния в БД
                    $state_from = State::find()->where(['name' => $individual_name])->one();
                    // Обход массива объектных свойств индивида
                    foreach ($item[3] as $object_property_name => $object_property_value) {
                        // Поиск состояния в БД
                        $state_to = State::find()->where(['name' => $object_property_value])->one();
                        // Создание нового перехода
                        $transition_model = new Transition();
                        $transition_model->name = $object_property_name;
                        $transition_model->state_from = $state_from->id;
                        $transition_model->state_to = $state_to->id;
                        $transition_model->name_property = 'name property';
                        $transition_model->operator_property = TransitionProperty::EQUALLY_OPERATOR;
                        $transition_model->value_property = 'value property';
                        $transition_model->save();
                    }
                }
            }
        }
    }
}