<?php

namespace app\modules\eete\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
use app\modules\main\models\Diagram;

/**
 * This is the model class for table "{{%tree_diagram}}".
 *
 * @property int $id
 * @property int $created_at
 * @property int $updated_at
 * @property int $mode
 * @property int $tree_view
 *
 * @property Level[] $levels
 * @property Node[] $nodes
 * @property Sequence[] $sequences
 * @property Diagram[] $diagram
 */
class TreeDiagram extends \yii\db\ActiveRecord
{
    const EXTENDED_TREE_MODE = 0; // Расширенное дерево
    const CLASSIC_TREE_MODE = 1;  // Классическое дерево

    /**
     * @return string table name
     */
    public static function tableName()
    {
        return '{{%tree_diagram}}';
    }

    /**
     * @return array the validation rules
     */
    public function rules()
    {
        return [
            [['mode'], 'default', 'value' => null],
            [['mode'], 'integer'],
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
            'id' => Yii::t('app', 'TREE_DIAGRAM_MODEL_ID'),
            'created_at' => Yii::t('app', 'TREE_DIAGRAM_MODEL_CREATED_AT'),
            'updated_at' => Yii::t('app', 'TREE_DIAGRAM_MODEL_UPDATED_AT'),
            'mode' => Yii::t('app', 'TREE_DIAGRAM_MODEL_MODE'),
            'diagram' => Yii::t('app', 'TREE_DIAGRAM_MODEL_DIAGRAM'),
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * Получение списка режимов деревьев диаграмм.
     *
     * @return array - массив всех возможных статусов
     */
    public static function getModesArray()
    {
        return [
            self::EXTENDED_TREE_MODE => Yii::t('app', 'TREE_DIAGRAM_MODEL_EXTENDED_TREE_MODE'),
            self::CLASSIC_TREE_MODE => Yii::t('app', 'TREE_DIAGRAM_MODEL_CLASSIC_TREE_MODE'),
        ];
    }

    /**
     * Получение названия типа диаграмм.
     *
     * @return mixed
     */
    public function getModesName()
    {
        return ArrayHelper::getValue(self::getModesArray(), $this->mode);
    }

    /**
     * Получение списка режимов деревьев диаграмм на английском.
     *
     * @return array - массив всех возможных статусов на английском
     */
    public static function getModesArrayEn()
    {
        return [
            self::EXTENDED_TREE_MODE => 'Extended tree',
            self::CLASSIC_TREE_MODE => 'Classic tree',
        ];
    }

    /**
     * Получение названия типа диаграмм на английском.
     *
     * @return mixed
     */
    public function getModesNameEn()
    {
        return ArrayHelper::getValue(self::getModesArrayEn(), $this->mode);
    }

    /**
     * Получение название диаграммы.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDiagram()
    {
        return $this->hasOne(Diagram::className(), ['id' => 'diagram']);
    }
}