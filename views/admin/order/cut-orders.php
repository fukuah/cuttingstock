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

$canvasAdaptiveCSS = <<<CSS
#responsive-canvas{
    width: 100%;
}
CSS;

$sheetObjectJS = '';

$canvasAdaptiveJS = <<<JS
    function resize(){   
    var canvas = $("#cutting");
    canvas.outerHeight($(window).height() - canvas.offset().top - Math.abs(canvas.outerHeight(true) - canvas.outerHeight()));
  }
  $(document).ready(function(){
        var canvas = document.getElementById('responsive-canvas');
	    ctx = canvas.getContext('2d');
	    ctx.fillStyle = "silver";
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        
        ctx.fillStyle = "silver";
        ctx.fillRect(50, 50, canvas.width, canvas.height);
        resize();
        $(window).on("resize", function(){                      
            resize();
        });
  });
JS;


$this->registerCSS($canvasAdaptiveCSS);

$this->registerJS($canvasAdaptiveJS, \yii\web\View::POS_END)
?>
<div class="order-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <div>
        <p></p>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <button id="prev-list">&laquoПредыдущий</button>
        </div>
        <div class="col-sm-4 text-center">
            <label aria-label="Лист:">Лист: </label>
            <input aria-label="Лист" type="text" size="4"/>
            <button id="go-to-list">Перейти</button>
        </div>
        <div class="col-sm-4 text-right">
            <button id="next-list">Следующий&raquo</button>
        </div>
        <br>
        <div id="'outer" class="col-md-12">
            <canvas id='responsive-canvas'></canvas>
        </div>
    </div>

    <pre>
        <?php print_r($sheets); ?>
    </pre>

</div>