<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Order */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Заказы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="order-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
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
                'attribute' => 'id',
                'format' => 'html',
                'label' => 'Содержание',
                'value' => function ($model) {
                    $html = '';
                    foreach ($model->orderStock as $item) {
                        $html .= "<strong>Длина:</strong> {$item->length_mm} <strong>Ширина:</strong>  {$item->width_mm}\t<strong>Количество:</strong> {$item->count} <br>";
                    }
                    return $html;
                }
            ]
        ],
    ]) ?>

</div>
