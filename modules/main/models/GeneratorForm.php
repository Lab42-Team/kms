<?php

namespace app\modules\main\models;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;

class GeneratorForm extends Model
{
    public $platform;       // Имя платформы

    const PLATFORM_AI_MYLOGIC = 0;  // Платформа MyLogic

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['platform'], 'required'],
            [['platform'], 'integer'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'platform' => Yii::t('app', 'GENERATOR_FORM_PLATFORM'),
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * Получение списка платформ.
     *
     * @return array - массив всех возможных статусов
     */
    public static function getPlatformsArray()
    {
        return [
            self::PLATFORM_AI_MYLOGIC => Yii::t('app', 'GENERATOR_FORM_PLATFORM_AI_MYLOGIC'),
        ];
    }

    /**
     * Получение названия платформы.
     *
     * @return mixed|null
     * @throws \Exception
     */
    public function getPlatformsName()
    {
        return ArrayHelper::getValue(self::getPlatformsArray(), $this->platform);
    }

}
