<?php

namespace app\modules\main\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%virtual_assistant_model}}".
 *
 * @property int $id
 * @property int $created_at
 * @property int $updated_at
 * @property int $dialogue_model
 * @property int $target_model
 * @property int $type
 *
 * @property VirtualAssistant $virtual_assistant_id
 *
 */
class VirtualAssistantModel extends \yii\db\ActiveRecord
{
    const KNOWLEDGE_BASE_MODEL_TYPE = 0;
    const CONVERSATIONAL_INTERFACE_MODEL_TYPE = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%virtual_assistant_model}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'dialogue_model', 'target_model', 'virtual_assistant_id'], 'required'],
            [['type', 'dialogue_model', 'target_model', 'virtual_assistant_id'], 'integer'],

            [['dialogue_model'], 'exist', 'skipOnError' => true, 'targetClass' => Diagram::className(),
                'targetAttribute' => ['dialogue_model' => 'id']],
            [['target_model'], 'exist', 'skipOnError' => true, 'targetClass' => Diagram::className(),
                'targetAttribute' => ['target_model' => 'id']],
            [['virtual_assistant_id'], 'exist', 'skipOnError' => true, 'targetClass' => VirtualAssistant::className(),
                'targetAttribute' => ['virtual_assistant_id' => 'id']]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'VIRTUAL_ASSISTANT_MODEL_MODEL_ID'),
            'created_at' => Yii::t('app', 'VIRTUAL_ASSISTANT_MODEL_MODEL_CREATED_AT'),
            'updated_at' => Yii::t('app', 'VIRTUAL_ASSISTANT_MODEL_MODEL_UPDATED_AT'),
            'dialogue_model' => Yii::t('app', 'VIRTUAL_ASSISTANT_MODEL_MODEL_DIALOGUE_MODEL'),
            'target_model' => Yii::t('app', 'VIRTUAL_ASSISTANT_MODEL_MODEL_TARGET_MODEL'),
            'type' => Yii::t('app', 'VIRTUAL_ASSISTANT_MODEL_MODEL_TYPE'),
            'virtual_assistant_id' => Yii::t('app', 'VIRTUAL_ASSISTANT_MODEL_MODEL_VIRTUAL_ASSISTANT_ID'),
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * Получение списка типов.
     *
     * @return array - массив всех возможных типов
     */
    public static function getTypesArray()
    {
        return [
            self::KNOWLEDGE_BASE_MODEL_TYPE => Yii::t('app', 'VIRTUAL_ASSISTANT_MODEL_MODEL_KNOWLEDGE_BASE_MODEL_TYPE'),
            self::CONVERSATIONAL_INTERFACE_MODEL_TYPE => Yii::t('app', 'VIRTUAL_ASSISTANT_MODEL_MODEL_CONVERSATIONAL_INTERFACE_MODEL_TYPE'),
        ];
    }

    /**
     * Получение названия типа диаграмм.
     *
     * @return mixed|null
     * @throws \Exception
     */
    public function getTypeName()
    {
        return ArrayHelper::getValue(self::getTypesArray(), $this->type);
    }


    /**
     * Gets query for [[DialogueModel]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDialogueModel()
    {
        return $this->hasOne(Diagram::className(), ['id' => 'dialogue_model']);
    }

    /**
     * Gets query for [[TargetModel]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTargetModel()
    {
        return $this->hasOne(Diagram::className(), ['id' => 'target_model']);
    }

    /**
     * Gets query for [[VirtualAssistantId]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVirtualAssistantId()
    {
        return $this->hasOne(VirtualAssistant::className(), ['id' => 'virtual_assistant_id']);
    }
}
