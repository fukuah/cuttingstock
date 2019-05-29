<?php

use yii\widgets\DetailView;

$this->title = 'Мои заказы';
?>

<h2>Мои заказы</h2>
<div class="row">
    <?php foreach ($models as $model) { ?>
        <div class="col-md-6">
            <h4>№<?= $model->id ?></h4>

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
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
    <?php } ?>
</div>
