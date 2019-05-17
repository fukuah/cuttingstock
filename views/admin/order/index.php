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
            ],
            'id',
            [
                'attribute' => 'user_id',
                'value' => function ($model) {
                    return $model->user->getFioShort();
                }
            ],
            [
                'attribute' => 'provider_id',
                'value' => function ($model) {
                    return $model->provider->company_name;
                }
            ],
            'status',
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
