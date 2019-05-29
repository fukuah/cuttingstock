<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заказы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= Html::beginForm(['/admin/order/cut-orders']) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\CheckboxColumn',
                'content' => function ($model, $key, $index, $column) {
                    return '<input  type="checkbox" name="selection[]" value="' . $model->id . '" ' . (($model->status) ? 'disabled' : '') . '>';
                },
            ],
            'id',
            [
                'attribute' => 'user_id',
                'value' => function ($model) {
                    return $model->user->getFioShort();
                }
            ],
            [
                'attribute' => 'material_id',
                'value' => function ($model) {
                    return $model->material->material_name;
                }
            ],
            [
                'attribute' => 'status',
                'format' => 'html',
                'value' => function ($model) {
                    return ($model->status) ? '<p class="text-success">Готов</p>' : '<p class="text-secondary">Выполняется</p>';
                }
            ],
            'price',
            'created_at',
            'served_at',
            [
                'class' => 'yii\grid\ActionColumn',

                'template' => '{view}'
            ],
        ],
    ]); ?>

    <?= Html::submitButton('Распилить выбранные заказы', ['class' => 'btn btn-primary']) ?>

    <?= Html::endForm() ?>

</div>
