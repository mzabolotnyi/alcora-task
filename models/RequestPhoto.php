<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "request_photo".
 *
 * @property integer $request_id
 * @property string $url
 *
 * @property Request $request
 */
class RequestPhoto extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'request_photo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['request_id', 'url'], 'required'],
            ['request_id', 'exist', 'targetClass' => Request::className(), 'targetAttribute' => ['request_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'request_id' => 'ID заявки',
            'url' => 'URL фото',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequest()
    {
        return $this->request_id === null ? null : $this->hasOne(Request::className(), ['id' => 'request_id']);
    }
}
