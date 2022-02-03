<?php

namespace app\modules\main\models;

use Yii;
use yii\base\Model;

/**
 * Class OWLFileForm.
 */
class OWLFileForm extends Model
{
    public $owl_file;                 // OWL-файл онтологии
    public $class;                   // Класс
    public $subclass_relation;       // Отношение наследования (класс-подкласс)
    public $class_object_property;   // Отношение между классами (объектные свойства)
    public $class_datatype_property; // Свойства класса (свойства-значений)

    public $individual;                   // Индивид (экземпляр класса)
    public $is_a_relation;                // Отношение между классом и его экземпляром
    public $individual_object_property;   // Отношение между индивидами (объектные свойства)
    public $individual_datatype_property; // Свойства индивида (свойства-значений)

    /**
     * @return array the validation rules
     */
    public function rules()
    {
        return array(
            array(['owl_file'], 'required'),
            array(['owl_file'], 'file', 'extensions'=>'owl', 'checkExtensionByMimeType' => false),
            array(['class', 'subclass_relation', 'class_object_property', 'class_datatype_property',
                'individual', 'is_a_relation', 'individual_object_property', 'individual_datatype_property'], 'safe'),
        );
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return array(
            'owl_file' => Yii::t('app', 'OWL_FILE_FORM_OWL_FILE'),
            'class' => Yii::t('app', 'OWL_FILE_FORM_CLASS'),
            'subclass_relation' => Yii::t('app', 'OWL_FILE_FORM_SUBCLASS_RELATION'),
            'class_object_property' => Yii::t('app', 'OWL_FILE_FORM_CLASS_OBJECT_PROPERTY'),
            'class_datatype_property' => Yii::t('app', 'OWL_FILE_FORM_CLASS_DATATYPE_PROPERTY'),
            'individual' => Yii::t('app', 'OWL_FILE_FORM_INDIVIDUAL'),
            'is_a_relation' => Yii::t('app', 'OWL_FILE_FORM_IS_A_RELATION'),
            'individual_object_property' => Yii::t('app', 'OWL_FILE_FORM_INDIVIDUAL_OBJECT_PROPERTY'),
            'individual_datatype_property' => Yii::t('app', 'OWL_FILE_FORM_INDIVIDUAL_DATATYPE_PROPERTY'),
        );
    }
}