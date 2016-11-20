<?php

namespace app\models;


use Yii;
use yii\base\Model;
use yii\web\BadRequestHttpException;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    /**
     * @var UploadedFile[]
     */
    public $imageFiles;

    public function rules()
    {
        return [
            [['imageFiles'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg', 'maxFiles' => 5],
        ];
    }

    /**
     * Returns array links of uploaded files or 'fasle' if fail
     * @return array
     * @throws BadRequestHttpException
     */
    public function upload()
    {
        $uploadedFiles = [];

        if ($this->validate()) {

            foreach ($this->imageFiles as $file) {

                $fileName = Yii::$app->getSecurity()->generateRandomString(8) . '_' . time() . '.' . $file->extension;
                $filePath = 'uploads/' . $fileName;

                $file->saveAs($filePath);

                $uploadedFiles[] = Yii::getAlias('@web') . '/' . $filePath;
            }

            return $uploadedFiles;

        } else {
            throw new BadRequestHttpException();
        }
    }
}