<?php

namespace app\modules\stde\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%transition}}".
 *
 * @property int $id
 * @property int $created_at
 * @property int $updated_at
 * @property string $name
 * @property string $description
 * @property int $state_from
 * @property int $state_to
 *
 * @property State $stateFrom
 * @property State $stateTo
 * @property TransitionProperty[] $transitionProperties
 */
class Transition extends \yii\db\ActiveRecord
{
    public $name_property;
    public $description_property;
    public $operator_property;
    public $value_property;

    /**
     * @return string table name
     */
    public static function tableName()
    {
        return '{{%transition}}';
    }

    /**
     * @return array the validation rules
     */
    public function rules()
    {
        return [
            [['name', 'state_from', 'state_to', 'name_property', 'operator_property', 'value_property'], 'required'],
            [['state_from', 'state_to', 'operator_property'], 'integer'],
            [['name', 'name_property', 'value_property'], 'string', 'max' => 255],
            [['description', 'description_property'], 'string'],
            [['state_from'], 'exist', 'skipOnError' => true, 'targetClass' => State::className(),
                'targetAttribute' => ['state_from' => 'id']],
            [['state_to'], 'exist', 'skipOnError' => true, 'targetClass' => State::className(),
                'targetAttribute' => ['state_to' => 'id']],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'TRANSITION_MODEL_ID'),
            'created_at' => Yii::t('app', 'TRANSITION_MODEL_CREATED_AT'),
            'updated_at' => Yii::t('app', 'TRANSITION_MODEL_UPDATED_AT'),
            'name' => Yii::t('app', 'TRANSITION_MODEL_NAME'),
            'description' => Yii::t('app', 'TRANSITION_MODEL_DESCRIPTION'),
            'state_from' => Yii::t('app', 'TRANSITION_MODEL_STATE_FROM'),
            'state_to' => Yii::t('app', 'TRANSITION_MODEL_STATE_TO'),
            'name_property' => Yii::t('app', 'TRANSITION_MODEL_NAME_PROPERTY'),
            'description_property' => Yii::t('app', 'TRANSITION_MODEL_DESCRIPTION_PROPERTY'),
            'operator_property' => Yii::t('app', 'TRANSITION_MODEL_OPERATOR_PROPERTY'),
            'value_property' => Yii::t('app', 'TRANSITION_MODEL_VALUE_PROPERTY'),
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
    public function getStateFrom()
    {
        return $this->hasOne(State::className(), ['id' => 'state_from']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStateTo()
    {
        return $this->hasOne(State::className(), ['id' => 'state_to']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransitionProperties()
    {
        return $this->hasMany(TransitionProperty::className(), ['transition' => 'id']);
    }
}