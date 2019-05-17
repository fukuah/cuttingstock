<?php
/**
 * Created by PhpStorm.
 * User: Alexei
 * Date: 12.05.2019
 * Time: 21:52
 */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'Регистрация';
?>

<div class="site-registration">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Для регистрации заполните форму:</p>

    <?php $form = ActiveForm::begin([
        'id' => 'registration-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

    <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
    <?= $form->field($model, 'email')->textInput() ?>
    <?= $form->field($model, 'last_name')->textInput() ?>
    <?= $form->field($model, 'first_name')->textInput() ?>
    <?= $form->field($model, 'middle_name')->textInput() ?>
    <?= $form->field($model, 'password')->passwordInput() ?>
    <?= $form->field($model, 'password_repeat')->passwordInput() ?>

    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

    <div class="col-lg-offset-1" style="color:#999;">
        <a>Забыли пароль?</a>
    </div>
</div>
