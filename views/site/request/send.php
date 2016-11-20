<?php

/* @var $this yii\web\View */
/* @var $model app\models\Request */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Подать заявку';
?>

<div class="page-request">

    <div class="header-img">
        <span class="fa fa-venus-mars fa-2x"></span>
    </div>

    <?php if (Yii::$app->session->hasFlash('sendRequestFormSubmitted')): ?>

        <h3>
            Ваша заявка принята
            <small>На E-mail было отправлено письмо с информацией о заявке</small>
        </h3>

        <div class="row m-t-30">
            <div class="col-sm-12 text-center">
                <?= Html::a('Ок', Url::home(), [
                    'class' => 'btn btn-lg btn-go',
                ]);
                ?>
            </div>
        </div>

    <?php else: ?>

        <h3>
            <?= Html::encode($this->title) ?>
            <small>Все поля обязательны для заполнения. Заявка приходит на E-mail</small>
        </h3>

        <?= $this->render('_form', [
            'model' => $model,
        ]); ?>

    <?php endif; ?>

</div>