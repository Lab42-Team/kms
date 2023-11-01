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
            [['name', 'status', 'author'], 'required'],
            ['description', 'default', 'value' => null],
            [['status', 'author'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 600],
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
            'status' => Yii::t('app', 'VIRTUAL_ASSISTANT_MODEL_STATUS'),
            'author' => Yii::t('app', 'VIRTUAL_ASSISTANT_MODEL_AUTHOR'),
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
}
