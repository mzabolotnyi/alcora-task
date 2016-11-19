<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "transaction".
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property integer $age
 * @property integer $height
 * @property integer $weight
 * @property string $city
 * @property integer $rent_equipment
 * @property integer $english_level
 * @property integer $created_at
 * @property integer $updated_at
 */
class Request extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'request';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'email', 'age', 'height', 'weight', 'city', 'rent_equipment', 'english_level'], 'required'],
            ['email', 'email'],
            [['name', 'city'], 'string', 'max' => 255],
            ['age', 'integer', 'min' => 18, 'max' => 150],
            ['height', 'integer', 'min' => 1, 'max' => 300],
            ['weight', 'integer', 'min' => 1, 'max' => 999],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Имя',
            'email' => 'E-mail',
            'age' => 'Возраст',
            'height' => 'Рост',
            'weight' => 'Вес',
            'city' => 'Город',
            'rent_equipment' => 'Нужна ли техника в аренду',
            'english_level' => 'Знание английского',
        ];
    }

    /**
     * @return array of the allowed values for $rent_equipment property
     */
    public function getAllowedValuesRentEquipment()
    {
        return [
            'нет',
            'да, только камера',
            'да, компьютер и камера',
        ];
    }

    /**
     * @return array of the allowed values for $english_level property
     */
    public function getAllowedValuesEnglishLevel()
    {
        return [
            'без знания',
            'базовый',
            'средний',
            'высокий',
            'превосходный',
        ];
    }

    /**
     * Submit request
     * @return bool whether submiting successful
     */
    public function submit()
    {
        $result = false;

        if ($this->validate()) {

            $result = $this->save();

            if ($result) {
                $this->sendToUser();
                $this->sendToAdmin();
            }
        }

        return $result;
    }

    /**
     * Sends request info to user email
     */
    protected function sendToUser()
    {
        Yii::$app->mailer->compose()
            ->setTo($this->email)
            ->setFrom([Yii::$app->params['adminEmail'] => Yii::$app->name])
            ->setSubject('Ваша заявка принята')
            ->setTextBody($this->getRequestInfo())
            ->send();
    }

    /**
     * Sends request info to admin email
     */
    protected function sendToAdmin()
    {
        Yii::$app->mailer->compose()
            ->setTo(Yii::$app->params['adminEmail'])
            ->setFrom([$this->email => $this->name])
            ->setSubject('Новая заявка')
            ->setTextBody($this->getRequestInfo())
            ->send();
    }

    /**
     * Returns request info
     * @return string
     */
    protected function getRequestInfo()
    {
        return 'test mail';
    }
}
