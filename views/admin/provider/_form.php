<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Material */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="provider-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-3"><?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?></div>

        <div class="col-md-3"><?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?></div>

        <div class="col-md-3"><?= $form->field($model, 'middle_name')->textInput(['maxlength' => true]) ?></div>

        <div class="col-md-3"><?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?></div>
    </div>
    <div class="row">
        <div class="col-md-6"><?= $form->field($model, 'price_cut')->textInput(['maxlength' => true]) ?></div>

        <div class="col-md-6"><?= $form->field($model, 'price_100mm2')->textInput(['maxlength' => true]) ?></div>
    </div>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'company_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'company_description')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
