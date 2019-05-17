<?php
/**
 * Created by PhpStorm.
 * User: Alexei
 * Date: 12.05.2019
 * Time: 16:08
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Заказать распил материалов';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="order-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-12"><?= $form->field($model, 'providerId')->dropDownList($providerList, ['prompt' => '--Выберите поставщика--']) ?></div>
        <div class="col-md-3"><?= $form->field($model, 'length')->textInput(['maxlength' => true]) ?></div>

        <div class="col-md-3"><?= $form->field($model, 'width')->textInput(['maxlength' => true]) ?></div>

        <div class="col-md-3"><?= $form->field($model, 'count')->textInput(['maxlength' => true]) ?></div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Заказать', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>