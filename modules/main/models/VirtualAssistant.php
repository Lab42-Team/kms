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
            [['name', 'status', 'author', 'dialogue_model', 'knowledge_base_model'], 'required'],
            ['description', 'default', 'value' => null],
            [['status', 'author', 'dialogue_model', 'knowledge_base_model'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 600],
            [['author'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(),
                'targetAttribute' => ['author' => 'id']],
            [['dialogue_model'], 'exist', 'skipOnError' => true, 'targetClass' => Diagram::className(),
                'targetAttribute' => ['dialogue_model' => 'id']],
            [['knowledge_base_model'], 'exist', 'skipOnError' => true, 'targetClass' => Diagram::className(),
                'targetAttribute' => ['knowledge_base_model' => 'id']]
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
     * @return mixed|null
     * @throws \Exception
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
