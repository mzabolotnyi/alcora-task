<?php

namespace app\controllers;

use app\models\AjaxFilter;
use app\models\UploadForm;
use Yii;
use yii\web\Response;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\Request;
use yii\web\ServerErrorHttpException;
use yii\web\UploadedFile;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'ajax' => [
                'class' => AjaxFilter::className(),
                'actions' => [
                    'upload-files' => '',
                    'delete-file' => '',
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'upload-files' => ['post'],
                    'delete-file' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Displays page for sending request
     * @return string
     */
    public function actionSendRequest()
    {
        $model = new Request();

        if ($model->load(Yii::$app->request->post()) && $model->submit()) {

            if (isset($_POST['photos'])) {
                $model->bindPhotos($_POST['photos']);
            }

            Yii::$app->session->setFlash('sendRequestFormSubmitted');
            return $this->refresh();
        }

        return $this->render('request/send', [
            'model' => $model,
        ]);
    }

    /**
     * Uploads files and returns array of links to those files
     * @return array
     * @throws ServerErrorHttpException
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionUploadFiles()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new UploadForm();
        $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');

        if ($result = $model->upload()) {
            // files was uploaded successfully
            return $result;
        } else {
            throw new ServerErrorHttpException('Не удалось загрузить фото');
        }
    }

    /**
     * Deletes file by url
     * @return bool
     * @throws ServerErrorHttpException
     */
    public function actionDeleteFile()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (isset($_POST['url']) && unlink($_SERVER['DOCUMENT_ROOT'] . '/' . $_POST['url'])) {
            // file was deleted successfully
            return true;
        } else {
            throw new ServerErrorHttpException('Не удалось  фото');
        }
    }
}
