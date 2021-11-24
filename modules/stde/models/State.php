<?php

namespace app\modules\stde\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
use app\modules\main\models\Diagram;

/**
 * This is the model class for table "state".
 *
 * @property int $id
 * @property int $created_at
 * @property int $updated_at
 * @property string $name
 * @property int $type
 * @property string $description
 * @property int $indent_x
 * @property int $indent_y
 * @property int $diagram
 *
 * @property Diagram $diagramFk
 * @property StateProperty[] $stateProperties
 * @property Transition[] $transitions
 * @property Transition[] $transitionsFk
 */
class State extends \yii\db\ActiveRecord
{
    const INITIAL_STATE_TYPE = 0; // Тип начальное (инициирующее) состояние
    const COMMON_STATE_TYPE = 1;  // Тип обычного состояния

    /**
     * @return string table name
     */
    public static function tableName()
    {
        return '{{%state}}';
    }

    /**
     * @return array the validation rules
     */
    public function rules()
    {
        return [
            [['name', 'diagram'], 'required'],
            [['type', 'indent_x', 'indent_y', 'diagram'], 'integer'],
            [['indent_x', 'indent_y'], 'default', 'value' => 0],
            [['name'], 'string', 'max' => 255],
            [['description'], 'string'],
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
            'id' => Yii::t('app', 'STATE_MODEL_ID'),
            'created_at' => Yii::t('app', 'STATE_MODEL_CREATED_AT'),
            'updated_at' => Yii::t('app', 'STATE_MODEL_UPDATED_AT'),
            'name' => Yii::t('app', 'STATE_MODEL_NAME'),
            'type' => Yii::t('app', 'STATE_MODEL_TYPE'),
            'description' => Yii::t('app', 'STATE_MODEL_DESCRIPTION'),
            'indent_x' => Yii::t('app', 'STATE_MODEL_INDENT_X'),
            'indent_y' => Yii::t('app', 'STATE_MODEL_INDENT_Y'),
            'diagram' => Yii::t('app', 'STATE_MODEL_DIAGRAM'),
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
     * @return \yii\db\ActiveQuery
     */
    public function getStateProperties()
    {
        return $this->hasMany(StateProperty::className(), ['state' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransitions()
    {
        return $this->hasMany(Transition::className(), ['state_from' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransitionsFk()
    {
        return $this->hasMany(Transition::className(), ['state_to' => 'id']);
    }

    /**
     * Получение списка типов состояний.
     * @return array - массив всех возможных типов состояний
     */
    public static function getTypesArray()
    {
        return [
            self::INITIAL_STATE_TYPE => Yii::t('app', 'STATE_MODEL_INITIAL_STATE_TYPE'),
            self::COMMON_STATE_TYPE => Yii::t('app', 'STATE_MODEL_COMMON_STATE_TYPE'),
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
            self::INITIAL_STATE_TYPE => 'Initial state',
            self::COMMON_STATE_TYPE => 'State',
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