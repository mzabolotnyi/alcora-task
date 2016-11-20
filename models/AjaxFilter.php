<?php

namespace app\models;

use yii\base\Behavior;
use yii\web\Controller;
use yii\web\HttpException;

/*
* Ajax filter for yii2
*
* public function behaviors()
*	{
*		return [
*			'ajax' => [
*				'class' => AjaxFilter::className(),
*				'actions' => [
*					'actionName' => ['ajax'],
*				],
*			],
*		];
*	}
 */

class AjaxFilter extends Behavior
{
    public $actions = [];

    public function events()
    {
        return [Controller::EVENT_BEFORE_ACTION => 'beforeAction'];
    }

    public function beforeAction($event)
    {
        $action = $event->action->id;

        if (isset($this->actions[$action])) {
            if (!\Yii::$app->request->getIsAjax()) {
                throw new HttpException(400, 'For this action  allowed only Ajax requests');
            }
        }
    }
} 