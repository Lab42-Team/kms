<?php

namespace app\modules\main\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%virtual_assistant}}".
 *
 * @property int $id
 * @property int $created_at
 * @property int $updated_at
 * @property string $name
 * @property string|null $description
 * @property int $type
 * @property int $status
 * @property int $author
 * @property int $dialogue_model
 * @property int $knowledge_base_model
 *
 * @property User $author0
 * @property Diagram $dialogueModel
 * @property Diagram $knowledgeBaseModel
 */
class VirtualAssistant extends \yii\db\ActiveRecord
{
    const EVENT_TREE_TYPE = 0;               // Тип диаграммы дерево событий
    const STATE_TRANSITION_DIAGRAM_TYPE = 1; // Тип диаграммы переходов состояний

    const PUBLIC_STATUS = 0;  // Публичный статус
    const PRIVATE_STATUS = 1; // Приватный статус

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%virtual_assistant}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'author', 'dialogue_model', 'knowledge_base_model'], 'required'],
            [['type', 'status', 'author', 'dialogue_model', 'knowledge_base_model'], 'default', 'value' => null],
            [['type', 'status', 'author', 'dialogue_model', 'knowledge_base_model'], 'integer'],
            [['description'], 'string', 'max' => 600],
            [['name'], 'string', 'max' => 255],
            [['dialogue_model'], 'exist', 'skipOnError' => true, 'targetClass' => Diagram::className(),
                'targetAttribute' => ['dialogue_model' => 'id']],
            [['knowledge_base_model'], 'exist', 'skipOnError' => true, 'targetClass' => Diagram::className(),
                'targetAttribute' => ['knowledge_base_model' => 'id']],
            [['author'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(),
                'targetAttribute' => ['author' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'VIRTUAL_ASSISTANT_MODEL_ID'),
            'created_at' => Yii::t('app', 'VIRTUAL_ASSISTANT_MODEL_CREATED_AT'),
            'updated_at' => Yii::t('app', 'VIRTUAL_ASSISTANT_MODEL_UPDATED_AT'),
            'name' => Yii::t('app', 'VIRTUAL_ASSISTANT_MODEL_NAME'),
            'description' => Yii::t('app', 'VIRTUAL_ASSISTANT_MODEL_DESCRIPTION'),
            'type' => Yii::t('app', 'VIRTUAL_ASSISTANT_MODEL_TYPE'),
            'status' => Yii::t('app', 'VIRTUAL_ASSISTANT_MODEL_STATUS'),
            'author' => Yii::t('app', 'VIRTUAL_ASSISTANT_MODEL_AUTHOR'),
            'dialogue_model' => Yii::t('app', 'VIRTUAL_ASSISTANT_MODEL_DIALOGUE_MODEL'),
            'knowledge_base_model' => Yii::t('app', 'VIRTUAL_ASSISTANT_MODEL_KNOWLEDGE_BASE_MODEL'),
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }



    /**
     * Получение списка типов диаграмм.
     *
     * @return array - массив всех возможных типов диаграмм
     */
    public static function getTypesArray()
    {
        return [
            self::EVENT_TREE_TYPE => Yii::t('app', 'VIRTUAL_ASSISTANT_MODEL_EVENT_TREE_TYPE'),
            self::STATE_TRANSITION_DIAGRAM_TYPE =>
                Yii::t('app', 'VIRTUAL_ASSISTANT_MODEL_STATE_TRANSITION_DIAGRAM_TYPE'),
        ];
    }

    /**
     * Получение названия типа диаграмм.
     *
     * @return mixed
     */
    public function getTypeName()
    {
        return ArrayHelper::getValue(self::getTypesArray(), $this->type);
    }

    /**
     * Получение списка типов диаграмм на английском.
     *
     * @return array - массив всех возможных типов диаграмм на английском
     */
    public static function getTypesArrayEn()
    {
        return [
            self::EVENT_TREE_TYPE => 'Event tree',
            self::STATE_TRANSITION_DIAGRAM_TYPE => 'State transition diagram',
        ];
    }

    /**
     * Получение названия типа диаграмм на английском.
     *
     * @return mixed
     */
    public function getTypeNameEn()
    {
        return ArrayHelper::getValue(self::getTypesArrayEn(), $this->type);
    }

    /**
     * Получение списка статусов.
     *
     * @return array - массив всех возможных статусов
     */
    public static function getStatusesArray()
    {
        return [
            self::PUBLIC_STATUS => Yii::t('app', 'VIRTUAL_ASSISTANT_MODEL_PUBLIC_STATUS'),
            self::PRIVATE_STATUS => Yii::t('app', 'VIRTUAL_ASSISTANT_MODEL_PRIVATE_STATUS'),
        ];
    }

    /**
     * Получение названия типа диаграмм.
     *
     * @return mixed
     */
    public function getStatusName()
    {
        return ArrayHelper::getValue(self::getStatusesArray(), $this->status);
    }



    /**
     * Gets query for [[Author0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'author']);
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
     * Gets query for [[KnowledgeBaseModel]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKnowledgeBaseModel()
    {
        return $this->hasOne(Diagram::className(), ['id' => 'knowledge_base_model']);
    }
}
