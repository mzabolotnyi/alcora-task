<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = 'Главная';
?>

<div class="jumbotron">
    <div class="card">
        <h2>Тестовое задание на позицию PHP Developer</h2>

        <p class="lead">Исполнитель: Заболотный Максим</p>

        <p><?= Html::a('Перейти', Url::toRoute(['send-request']), [
                'class' => 'btn btn-lg btn-go',
            ]);
            ?></p>
    </div>
</div>
