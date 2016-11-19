<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\Request */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

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

        <div class="card">

            <?php $form = ActiveForm::begin(['id' => 'send-request-form']); ?>

            <div class="row">
                <div class="col-sm-6">
                    <?= $form->field($model, 'name', [
                        'inputOptions' => ['placeholder' => 'Имя'],
                        'template' => "<span class='input-addon fa fa-user-o'></span>{input}\n{error}",
                    ])->textInput(['autofocus' => true]) ?>
                </div>
                <div class="col-sm-6">
                    <?= $form->field($model, 'email', [
                        'inputOptions' => ['placeholder' => 'E-mail'],
                        'template' => "<span class='input-addon fa fa-envelope-o'></span>{input}\n{error}",
                    ]) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <?= $form->field($model, 'age', [
                        'inputOptions' => ['placeholder' => 'Возраст (полных лет)'],
                        'template' => "<span class='input-addon fa fa-calendar-check-o'></span>{input}\n{error}",
                    ]) ?>
                </div>
                <div class="col-sm-6">
                    <?= $form->field($model, 'height', [
                        'inputOptions' => ['placeholder' => 'Рост (см)'],
                        'template' => "<span class='input-addon fa fa-arrows-v'></span>{input}\n{error}",
                    ]) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <?= $form->field($model, 'weight', [
                        'inputOptions' => ['placeholder' => 'Вес (кг)'],
                        'template' => "<span class='input-addon fa fa-arrows-h'></span>{input}\n{error}",
                    ]) ?>
                </div>
                <div class="col-sm-6">
                    <?= $form->field($model, 'city', [
                        'inputOptions' => ['placeholder' => 'Город проживания'],
                        'template' => "<span class='input-addon fa fa-location-arrow'></span>{input}\n{error}",
                    ]) ?>
                </div>
            </div>

            <div class="row m-t-10">
                <div class="col-sm-12">
                    <?= $form->field($model, 'rent_equipment', [
                        'template' => "<span class='label-addon fa fa-tv'></span>{label}{input}\n{error}",
                    ])->radioList($model->getAllowedValuesRentEquipment(), [
                        'tag' => false,
                        'item' => function ($index, $label, $name, $checked, $value) {
                            return '<div class="radio radio-inline"><label>' .
                            Html::radio($name, $checked, ['value' => $value]) .
                            '<i class="input-helper"></i>' . $label . '</label></div>';
                        },
                    ]) ?>
                </div>
            </div>

            <div class="row m-t-10">
                <div class="col-sm-12">
                    <?= $form->field($model, 'english_level', [
                        'template' => "<span class='label-addon fa fa-book'></span>{label}{input}\n{error}",
                    ])->radioList($model->getAllowedValuesEnglishLevel(), [
                        'tag' => false,
                        'item' => function ($index, $label, $name, $checked, $value) {
                            return '<div class="radio radio-inline"><label>' .
                            Html::radio($name, $checked, ['value' => $value]) .
                            '<i class="input-helper"></i>' . $label . '</label></div>';
                        },
                    ]) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>

        </div>

        <div class="row m-t-30">
            <div class="col-sm-12 text-center">
                <button class="btn btn-lg btn-go" type="submit" form="send-request-form">Отправить</button>
            </div>
        </div>

    <?php endif; ?>

</div>