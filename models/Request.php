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
     * Maximum count of photos available for upload
     */
    const MAX_PHOTOS = 5;

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
    public function init()
    {
        parent::init();

        $this->rent_equipment = 0;
        $this->english_level = 0;
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
     * Binds photo urls to request
     * @param $photos
     */
    public function bindPhotos(&$photos)
    {
        if (!is_array($photos)) {
            return;
        }

        if (count($photos) > 5) {
            $photos = array_slice($photos, 0, self::MAX_PHOTOS);
        }

        foreach ($photos as $photoUrl) {
            $model = new RequestPhoto();
            $model->request_id = $this->id;
            $model->url = $photoUrl;
            $model->save();
        }
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
        $valuesRentEquipment = $this->getAllowedValuesRentEquipment();
        $valuesEnglishLevel = $this->getAllowedValuesEnglishLevel();

        return "Информация о заявке\n\n"
        . "Имя: $this->name\n"
        . "E-mail: $this->email\n"
        . "Возраст (лет): $this->age\n"
        . "Рост: $this->height см\n"
        . "Вес: $this->weight кг\n"
        . "Город: $this->city\n"
        . "Нужна ли техника в аренду: " . $valuesRentEquipment[$this->rent_equipment] . "\n"
        . "Уровень английского: " . $valuesEnglishLevel[$this->english_level];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhotos()
    {
        return $this->hasMany(RequestPhoto::className(), ['request_id' => 'id']);
    }
}
