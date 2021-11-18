<?php

namespace app\modules\stde\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%state_property}}".
 *
 * @property int $id
 * @property int $created_at
 * @property int $updated_at
 * @property string $name
 * @property string $description
 * @property int $operator
 * @property string $value
 * @property int $state
 *
 * @property State $stateFk
 */
class StateProperty extends \yii\db\ActiveRecord
{
    const EQUALLY_OPERATOR = 0;             // Оператор равно
    const MORE_OPERATOR = 1;                // Оператор больше
    const LESS_OPERATOR = 2;                // Оператор меньше
    const MORE_EQUAL_OPERATOR = 3;          // Оператор больше или равно
    const LESS_EQUAL_OPERATOR = 4;          // Оператор меньше или равно
    const NOT_EQUAL_OPERATOR = 5;           // Оператор не равно
    const APPROXIMATELY_EQUAL_OPERATOR = 6; // Оператор приблизительно равно

    /**
     * @return string table name
     */
    public static function tableName()
    {
        return '{{%state_property}}';
    }

    /**
     * @return array the validation rules
     */
    public function rules()
    {
        return [
            [['name', 'value', 'state'], 'required'],
            [['operator', 'state'], 'integer'],
            [['name', 'value'], 'string', 'max' => 255],
            [['description'], 'string'],

            // name, operator, value и state вместе должны быть уникальны, но только name будет получать сообщение об ошибке
            ['name', 'unique', 'targetAttribute' => ['name', 'operator', 'value', 'state'],
                'message' => Yii::t('app', 'MESSAGE_STATE_PROPERTY_ALREADY_IN_STATE')],

            [['state'], 'exist', 'skipOnError' => true, 'targetClass' => State::className(),
                'targetAttribute' => ['state' => 'id']],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'STATE_PROPERTY_MODEL_ID'),
            'created_at' => Yii::t('app', 'STATE_PROPERTY_MODEL_CREATED_AT'),
            'updated_at' => Yii::t('app', 'STATE_PROPERTY_MODEL_UPDATED_AT'),
            'name' => Yii::t('app', 'STATE_PROPERTY_MODEL_NAME'),
            'description' => Yii::t('app', 'STATE_PROPERTY_MODEL_DESCRIPTION'),
            'operator' => Yii::t('app', 'STATE_PROPERTY_MODEL_OPERATOR'),
            'value' => Yii::t('app', 'STATE_PROPERTY_MODEL_VALUE'),
            'state' => Yii::t('app', 'STATE_PROPERTY_MODEL_STATE'),
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
    public function getStateFk()
    {
        return $this->hasOne(State::className(), ['id' => 'state']);
    }

    /**
     * Получение списка операторов.
     * @return array - массив всех возможных операторов
     */
    public static function getOperatorArray()
    {
        return [
            self::EQUALLY_OPERATOR => Yii::t('app', 'STATE_PROPERTY_MODEL_EQUALLY_OPERATOR'),
            self::MORE_OPERATOR => Yii::t('app', 'STATE_PROPERTY_MODEL_MORE_OPERATOR'),
            self::LESS_OPERATOR => Yii::t('app', 'STATE_PROPERTY_MODEL_LESS_OPERATOR'),
            self::MORE_EQUAL_OPERATOR => Yii::t('app', 'STATE_PROPERTY_MODEL_MORE_EQUAL_OPERATOR'),
            self::LESS_EQUAL_OPERATOR => Yii::t('app', 'STATE_PROPERTY_MODEL_LESS_EQUAL_OPERATOR'),
            self::NOT_EQUAL_OPERATOR => Yii::t('app', 'STATE_PROPERTY_MODEL_NOT_EQUAL_OPERATOR'),
            self::APPROXIMATELY_EQUAL_OPERATOR => Yii::t('app', 'STATE_PROPERTY_MODEL_APPROXIMATELY_EQUAL_OPERATOR'),
        ];
    }

    /**
     * Получение названия оператора.
     * @return mixed
     */
    public function getOperatorName()
    {
        return ArrayHelper::getValue(self::getOperatorArray(), $this->operator);
    }
}