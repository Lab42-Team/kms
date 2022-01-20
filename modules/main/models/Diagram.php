<?php

namespace app\modules\main\models;

use Yii;
use yii\helpers\ArrayHelper;
use app\modules\main\models\User;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%diagram}}".
 *
 * @property int $id
 * @property int $created_at
 * @property int $updated_at
 * @property string $name
 * @property string $description
 * @property int $type
 * @property int $status
 * @property int $correctness
 * @property int $author
 *
 * @property User $user
 */
class Diagram extends \yii\db\ActiveRecord
{
    public $mode_tree_diagram;

    const EVENT_TREE_TYPE = 0;               // Тип диаграммы дерево событий
    const STATE_TRANSITION_DIAGRAM_TYPE = 1; // Тип диаграммы переходов состояний

    const PUBLIC_STATUS = 0;  // Публичный статус
    const PRIVATE_STATUS = 1; // Приватный статус

    const NOT_CHECKED_CORRECT = 0; // Корректность не проверялась
    const CORRECTLY_CORRECT = 1;   // Корректно
    const INCORRECTLY_CORRECT = 2; // Некорректно

    /**
     * @return string table name
     */
    public static function tableName()
    {
        return '{{%diagram}}';
    }

    /**
     * @return array the validation rules
     */
    public function rules()
    {
        return [
            [['name', 'author'], 'required'],
            [['type', 'status', 'author', 'correctness'], 'default', 'value' => null],
            [['type', 'status', 'author', 'correctness', 'mode_tree_diagram'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 600],
            [['author'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(),
                'targetAttribute' => ['author' => 'id']],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'DIAGRAM_MODEL_ID'),
            'created_at' => Yii::t('app', 'DIAGRAM_MODEL_CREATED_AT'),
            'updated_at' => Yii::t('app', 'DIAGRAM_MODEL_UPDATED_AT'),
            'name' => Yii::t('app', 'DIAGRAM_MODEL_NAME'),
            'description' => Yii::t('app', 'DIAGRAM_MODEL_DESCRIPTION'),
            'type' => Yii::t('app', 'DIAGRAM_MODEL_TYPE'),
            'status' => Yii::t('app', 'DIAGRAM_MODEL_STATUS'),
            'correctness' => Yii::t('app', 'DIAGRAM_MODEL_CORRECTNESS'),
            'author' => Yii::t('app', 'DIAGRAM_MODEL_AUTHOR'),
            'mode_tree_diagram' => Yii::t('app', 'TREE_DIAGRAM_MODEL_MODE'),
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
            self::EVENT_TREE_TYPE => Yii::t('app', 'DIAGRAM_MODEL_EVENT_TREE_TYPE'),
            self::STATE_TRANSITION_DIAGRAM_TYPE =>
                Yii::t('app', 'DIAGRAM_MODEL_STATE_TRANSITION_DIAGRAM_TYPE'),
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
            self::PUBLIC_STATUS => Yii::t('app', 'DIAGRAM_MODEL_PUBLIC_STATUS'),
            self::PRIVATE_STATUS => Yii::t('app', 'DIAGRAM_MODEL_PRIVATE_STATUS'),
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
     * Получение списка режимов деревьев диаграмм.
     *
     * @return array - массив всех возможных статусов
     */
    public static function getCorrectnessArray()
    {
        return [
            self::NOT_CHECKED_CORRECT => Yii::t('app', 'DIAGRAM_MODEL_NOT_CHECKED_CORRECT'),
            self::CORRECTLY_CORRECT => Yii::t('app', 'DIAGRAM_MODEL_CORRECTLY_CORRECT'),
            self::INCORRECTLY_CORRECT => Yii::t('app', 'DIAGRAM_MODEL_INCORRECTLY_CORRECT'),
        ];
    }

    /**
     * Получение названия типа диаграмм.
     *
     * @return mixed
     */
    public function getCorrectnessName()
    {
        return ArrayHelper::getValue(self::getCorrectnessArray(), $this->correctness);
    }

    /**
     * Получение имени автора диаграммы.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'author']);
    }
}