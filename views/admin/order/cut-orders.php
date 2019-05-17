<?php
/**
 * Created by PhpStorm.
 * User: Alexei
 * Date: 18.05.2019
 * Time: 2:27
 */

use yii\helpers\Html;

$this->title = 'Распил на заказы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <pre>
        <?php print_r($sheets); ?>
    </pre>

</div>