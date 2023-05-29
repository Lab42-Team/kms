<?php

namespace app\modules\stde\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
use app\modules\main\models\Diagram;

/**
 * This is the model class for table "start_to_end".
 *
 * @property int $id
 * @property int $created_at
 * @property int $updated_at
 * @property int $type
 * @property int $diagram
 * @property int $state
 *
 * @property Diagram $diagramFk
 * @property State $stateFk
 */

class StartToEnd extends \yii\db\ActiveRecord
{
    const START_TYPE = 0;         // Начало
    const END_TYPE = 1;           // Завершение

    /**
     * @return string table name
     */
    public static function tableName()
    {
        return '{{%start_to_end}}';
    }

    /**
     * @return array the validation rules
     */
    public function rules()
    {
        return [
            [['type', 'diagram'], 'required'],
            [['type', 'diagram', 'indent_x', 'indent_y'], 'integer'],
            [['indent_x', 'indent_y'], 'default', 'value' => 0],

            // 'type','diagram' вместе должны быть уникальны, т.е. допускается только по одному началу и завершению на диаграмму
            ['type', 'unique', 'targetAttribute' => ['type', 'diagram']],

            [['diagram'], 'exist', 'skipOnError' => true, 'targetClass' => Diagram::className(),
                'targetAttribute' => ['diagram' => 'id']],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'START_TO_END_MODEL_ID'),
            'created_at' => Yii::t('app', 'START_TO_END_MODEL_CREATED_AT'),
            'updated_at' => Yii::t('app', 'START_TO_END_MODEL_UPDATED_AT'),
            'type' => Yii::t('app', 'START_TO_END_MODEL_TYPE'),
            'indent_x' => Yii::t('app', 'START_TO_END_MODEL_INDENT_X'),
            'indent_y' => Yii::t('app', 'START_TO_END_MODEL_INDENT_Y'),
            'diagram' => Yii::t('app', 'START_TO_END_MODEL_DIAGRAM'),
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDiagramFk()
    {
        return $this->hasOne(Diagram::className(), ['id' => 'diagram']);
    }




    /**
     * --------------------------------------Получение списка типов ---------------------.
     * @return array - массив всех возможных типов состояний
     */
    public static function getTypesArray()
    {
        return [
            self::START_TYPE => Yii::t('app', 'START_TO_END_MODEL_START_TYPE'),
            self::END_TYPE => Yii::t('app', 'START_TO_END_MODEL_END_TYPE'),
        ];
    }

    /**
     * Получение названия типа состояния.
     * @return mixed
     */
    public function getTypeName()
    {
        return ArrayHelper::getValue(self::getTypesArray(), $this->type);
    }

    /**
     * Получение списка типов состояний на английском.
     *
     * @return array - массив всех возможных типов диаграмм на английском
     */
    public static function getTypesArrayEn()
    {
        return [
            self::START_TYPE => 'Start',
            self::END_TYPE => 'End',
        ];
    }

    /**
     * Получение названия типа состояний на английском.
     *
     * @return mixed
     */
    public function getTypeNameEn()
    {
        return ArrayHelper::getValue(self::getTypesArrayEn(), $this->type);
    }
}