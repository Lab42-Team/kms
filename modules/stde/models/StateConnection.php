<?php

namespace app\modules\stde\models;

use Yii;
use yii\behaviors\TimestampBehavior;


/**
 * This is the model class for table "state_connection".
 *
 * @property int $id
 * @property int $created_at
 * @property int $updated_at
 * @property int $diagram
 * @property int $state
 *
 * @property StartToEnd $diagramFk
 * @property State $stateFk
 */

class StateConnection extends \yii\db\ActiveRecord
{
    /**
     * @return string table name
     */
    public static function tableName()
    {
        return '{{%state_connection}}';
    }

    /**
     * @return array the validation rules
     */
    public function rules()
    {
        return [
            [['state', 'start_to_end'], 'required'],
            [['state', 'start_to_end'], 'integer'],

            // 'state','start_to_end' вместе должны быть уникальны, т.е. допускается только по одной связи между state и start_to_end
            ['state', 'unique', 'targetAttribute' => ['state', 'start_to_end']],

            [['start_to_end'], 'exist', 'skipOnError' => true, 'targetClass' => StartToEnd::className(),
                'targetAttribute' => ['start_to_end' => 'id']],

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
            'id' => Yii::t('app', 'STATE_CONNECTION_MODEL_ID'),
            'created_at' => Yii::t('app', 'STATE_CONNECTION_MODEL_CREATED_AT'),
            'updated_at' => Yii::t('app', 'STATE_CONNECTION_MODEL_UPDATED_AT'),
            'start_to_end' => Yii::t('app', 'STATE_CONNECTION_MODEL_START_TO_END'),
            'state' => Yii::t('app', 'STATE_CONNECTION_MODEL_STATE'),
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
        return $this->hasOne(StartToEnd::className(), ['id' => 'start_to_end']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStateFk()
    {
        return $this->hasOne(State::className(), ['id' => 'state']);
    }
}