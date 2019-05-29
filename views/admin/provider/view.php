<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Material */

$this->title = $model->material_name;
$this->params['breadcrumbs'][] = ['label' => 'Материалы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="provider-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [

            'material_name',
            'length_mm',
            'width_mm',
            'count',
            'price_cut',
            'price_100mm2',
            [
                'attribute' => 'id',
                'format' => 'html',
                'label' => 'Обрезки',
                'value' => function ($model) {
                    $html = '';
                    foreach ($model->providerStock as $item) {
                        $html .= "<strong>Длина:</strong> {$item->length_mm} <strong>Ширина:</strong>  {$item->width_mm}\t<strong>В наличии:</strong> {$item->count} <br>";
                    }
                    return $html;
                }
            ],
        ],
    ]) ?>

</div>
